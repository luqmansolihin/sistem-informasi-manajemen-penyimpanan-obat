<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionPatientStoreRequest;
use App\Http\Requests\TransactionPatientUpdateRequest;
use App\Models\Medicine;
use App\Models\MedicineSale;
use App\Models\Patient;
use App\Models\TransactionMedicine;
use App\Models\TransactionPatient;
use App\Models\TransactionPatientHasMedicine;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TransactionPatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $transactionPatients = TransactionPatient::query()
            ->with('patient:id,name')
            ->select(['id', 'patient_id', 'checkup_date', 'disease_name', 'medical_expense'])
            ->get();

        return view('pages.transactions.patients.index', compact('transactionPatients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $medicines = Medicine::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $patients = Patient::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('pages.transactions.patients.create', compact('medicines', 'patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionPatientStoreRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $medicineSales = [];

            $transactionPatientHasMedicines = $request->validated('transaction_patient');

            foreach ($transactionPatientHasMedicines as $key => $patientHasMedicine) {
                $transactionMedicines = TransactionMedicine::query()
                    ->whereIn('medicine_id', [$patientHasMedicine['medicine']])
                    ->where('qty_balance', '>', 0)
                    ->orderBy('expired_date')
                    ->orderBy('purchase_date')
                    ->get();

                if ($transactionMedicines->isEmpty()) { // Check the quantity balance of transaction medicine
                    DB::rollBack();

                    throw ValidationException::withMessages([
                        'transaction_patient.'.$key.'.quantity' => 'The Quantity field not have balance '
                    ]);
                }

                $tmpQty = $patientHasMedicine['quantity'];

                foreach ($transactionMedicines as $transactionMedicine) {
                    if ($tmpQty >= $transactionMedicine->qty_balance) {
                        $qtyToUpdate = $transactionMedicine->qty_balance;
                        $tmpQty -= $transactionMedicine->qty_balance;
                    } else {
                        $qtyToUpdate = $tmpQty;
                        $tmpQty -= $tmpQty;
                    }

                    $medicineSales[] = [
                        'medicine_id' => $transactionMedicine['medicine_id'],
                        'transaction_medicine_id' => $transactionMedicine['id'],
                        'qty_to_update' => $qtyToUpdate,
                    ];

                    if ($tmpQty <= 0)
                        break;
                }

                if ($tmpQty > 0) { // Check the quantity medicine to sale is fulfilled
                    DB::rollBack();

                    throw ValidationException::withMessages([
                        'transaction_patient.'.$key.'.quantity' => 'The Quantity field not have balance '
                    ]);
                }
            };

            $transactionPatient = TransactionPatient::query()
                ->create($request->safe()->only(['patient_id', 'checkup_date', 'disease_name', 'medical_expense']));

            foreach ($request->safe()->only('transaction_patient')['transaction_patient'] as $transactionPatientHasMedicine) {
                $transactionPatient->transactionPatientHasMedicines()
                    ->create([
                        'medicine_id' => $transactionPatientHasMedicine['medicine'],
                        'qty' => $transactionPatientHasMedicine['quantity'],
                    ]);
            }

            foreach ($transactionPatient->transactionPatientHasMedicines as $transactionPatientHasMedicine) {
                foreach ($medicineSales as $medicineSale) {
                    if ($medicineSale['medicine_id'] == $transactionPatientHasMedicine->medicine_id) {
                        MedicineSale::query()
                            ->create([
                                'transaction_medicine_id' => $medicineSale['transaction_medicine_id'],
                                'transaction_patient_has_medicine_id' => $transactionPatientHasMedicine->id,
                                'qty' => $medicineSale['qty_to_update'],
                            ]);

                        $transactionMedicine = TransactionMedicine::query()
                            ->where('id', $medicineSale['transaction_medicine_id'])
                            ->first();

                        $transactionMedicine->decrement('qty_balance', $medicineSale['qty_to_update']);
                    }
                }
            }

            DB::commit();

            return to_route('transactions.patients.index')->with('success', 'Transaction Patient has been created.');
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error($e->getTraceAsString());

            throw $e;
        }
    }

    /**
     * Show detail of the specified resource.
     */
    public function show(int $id): View
    {
        $transactionPatient = TransactionPatient::query()
            ->with('transactionPatientHasMedicines')
            ->findOrFail($id);

        return view('pages.transactions.patients.show', compact('transactionPatient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $transactionPatient = TransactionPatient::query()
            ->with('transactionPatientHasMedicines')
            ->findOrFail($id);

        $medicineWithTrasheds = Medicine::query()
            ->select(['id', 'name'])
            ->withTrashed()
            ->orderBy('name')
            ->get();

        $medicines = Medicine::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        $patients = Patient::query()
            ->select(['id', 'name'])
            ->withTrashed()
            ->orderBy('name')
            ->get();

        return view('pages.transactions.patients.edit', compact('transactionPatient', 'medicineWithTrasheds', 'medicines', 'patients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionPatientUpdateRequest $request, int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $transactionPatient = TransactionPatient::query()->findOrFail($id);

            $transactionPatient->update($request->safe()->only(['patient_id', 'checkup_date', 'disease_name', 'medical_expense']));

            // Transaction patient has medicine to delete
            $transactionPatientHasMedicines = TransactionPatientHasMedicine::query()
                ->whereNotIn('medicine_id', Arr::pluck($request->safe()->only(['transaction_patient'])['transaction_patient'], 'medicine'))
                ->where('transaction_patient_id', $id)
                ->get();

            foreach ($transactionPatientHasMedicines as $transactionPatientHasMedicine) {
                $medicineSales = MedicineSale::query()
                    ->where('transaction_patient_has_medicine_id', $transactionPatientHasMedicine->id)
                    ->orderBy('id')
                    ->get();

                foreach ($medicineSales as $medicineSale) {
                    $transactionMedicine = TransactionMedicine::query()
                        ->where('id', $medicineSale->transaction_medicine_id)
                        ->withTrashed()
                        ->first();

                    $transactionMedicine->increment('qty_balance', $medicineSale->qty);

                    MedicineSale::query()
                        ->where('id', $medicineSale->id)
                        ->delete();
                }

                TransactionPatientHasMedicine::query()->where('id', $transactionPatientHasMedicine->id)->delete();
            }

            // Transaction patient has medicine to update
            foreach ($request->validated('transaction_patient') as $key => $reqTransactionPatientHasMedicine) {
                $transactionPatientHasMedicine = TransactionPatientHasMedicine::query()
                    ->where('medicine_id', $reqTransactionPatientHasMedicine['medicine'])
                    ->where('transaction_patient_id', $transactionPatient->id)
                    ->first();

                if ($transactionPatientHasMedicine) { // Check transaction patient has medicine is existed
                    if ($reqTransactionPatientHasMedicine['quantity'] > $transactionPatientHasMedicine->qty) { // Check request quantity is upper existing quantity
                        $medicine = Medicine::query()
                            ->where('id', $reqTransactionPatientHasMedicine['medicine'])
                            ->first();

                        if (!$medicine) {
                            DB::rollBack();

                            throw ValidationException::withMessages([
                                'transaction_patient.'.$key.'.medicine' => 'The Medicine field is not exists'
                            ]);
                        }

                        $transactionMedicines = TransactionMedicine::query()
                            ->where('medicine_id', $reqTransactionPatientHasMedicine['medicine'])
                            ->where('qty_balance', '>', 0)
                            ->orderBy('expired_date')
                            ->orderBy('purchase_date')
                            ->get();

                        $qty_to_update = $reqTransactionPatientHasMedicine['quantity'] - $transactionPatientHasMedicine->qty;

                        $transactionPatientHasMedicine->increment('qty', $qty_to_update);

                        foreach ($transactionMedicines as $transactionMedicine) {
                            $transactionMedicine = TransactionMedicine::query()
                                ->where('id', $transactionMedicine->id)
                                ->first();

                            if ($transactionMedicine->qty_balance > $qty_to_update) {
                                $transactionMedicine->decrement('qty_balance', $qty_to_update);

                                $qty = $qty_to_update;
                                $qty_to_update -= $qty_to_update;
                            } else {
                                $qty = $transactionMedicine->qty_balance;
                                $qty_to_update -= $transactionMedicine->qty_balance;

                                $transactionMedicine->decrement('qty_balance', $transactionMedicine->qty_balance);
                            }

                            $medicineSale = MedicineSale::query()
                                ->where('transaction_medicine_id', $transactionMedicine->id)
                                ->where('transaction_patient_has_medicine_id', $transactionPatientHasMedicine->id)
                                ->first();

                            if ($medicineSale) {
                                $medicineSale->increment('qty', $qty);
                            } else {
                                MedicineSale::query()
                                    ->create([
                                        'transaction_medicine_id' => $transactionMedicine->id,
                                        'transaction_patient_has_medicine_id' => $transactionPatientHasMedicine->id,
                                        'qty' => $qty
                                    ]);
                            }

                            if ($qty_to_update <= 0)
                                break;
                        }

                        if ($qty_to_update > 0) { // Check the quantity medicine to sale is fulfilled
                            DB::rollBack();

                            throw ValidationException::withMessages([
                                'transaction_patient.'.$key.'.quantity' => 'The Quantity field not have balance '
                            ]);
                        }

                    } else if ($reqTransactionPatientHasMedicine['quantity'] < $transactionPatientHasMedicine->qty) { // Check request quantity is under existing quantity
                        $qty_to_remove = $transactionPatientHasMedicine->qty - $reqTransactionPatientHasMedicine['quantity'];

                        $transactionPatientHasMedicine->decrement('qty', $qty_to_remove);

                        $medicineSales = MedicineSale::query()
                            ->where('transaction_patient_has_medicine_id', $transactionPatientHasMedicine->id)
                            ->orderByDesc('id')
                            ->get();

                        foreach ($medicineSales as $medicineSale) {
                            $transactionMedicine = TransactionMedicine::query()
                                ->where('id', $medicineSale->transaction_medicine_id)
                                ->withTrashed()
                                ->first();

                            if ($medicineSale->qty > $qty_to_remove) {
                                $transactionMedicine->increment('qty_balance', $qty_to_remove);

                                $medicineSale = MedicineSale::query()
                                    ->where('id', $medicineSale->id)
                                    ->first();

                                $medicineSale->decrement('qty', $qty_to_remove);

                                $qty_to_remove -= $qty_to_remove;
                            } else {
                                $transactionMedicine->increment('qty_balance', $medicineSale->qty);

                                $qty_to_remove -= $medicineSale->qty;

                                MedicineSale::query()
                                    ->where('id', $medicineSale->id)
                                    ->delete();
                            }

                            if ($qty_to_remove <= 0)
                                break;
                        }
                    }
                } else {
                    $medicine = Medicine::query()
                        ->where('id', $reqTransactionPatientHasMedicine['medicine'])
                        ->first();

                    if (!$medicine) {
                        DB::rollBack();

                        throw ValidationException::withMessages([
                            'transaction_patient.'.$key.'.medicine' => 'The Medicine field is not exists'
                        ]);
                    }

                    $qty_to_update = $reqTransactionPatientHasMedicine['quantity'];

                    $transactionPatientHasMedicine = TransactionPatientHasMedicine::query()
                        ->create([
                            'transaction_patient_id' => $transactionPatient->id,
                            'medicine_id' => $reqTransactionPatientHasMedicine['medicine'],
                            'qty' => $qty_to_update,
                        ]);

                    $transactionMedicines = TransactionMedicine::query()
                        ->where('medicine_id', $reqTransactionPatientHasMedicine['medicine'])
                        ->where('qty_balance', '>', 0)
                        ->orderBy('expired_date')
                        ->orderBy('purchase_date')
                        ->get();

                    foreach ($transactionMedicines as $transactionMedicine) {
                        $transactionMedicine = TransactionMedicine::query()
                            ->where('id', $transactionMedicine->id)
                            ->first();

                        if ($transactionMedicine->qty_balance > $qty_to_update) {
                            $qty = $qty_to_update;
                            $qty_to_update -= $qty_to_update;
                        } else {
                            $qty = $transactionMedicine->qty_balance;
                            $qty_to_update -= $transactionMedicine->qty_balance;
                        }

                        $transactionMedicine->decrement('qty_balance', $qty);

                        MedicineSale::query()
                            ->create([
                                'transaction_medicine_id' => $transactionMedicine->id,
                                'transaction_patient_has_medicine_id' => $transactionPatientHasMedicine->id,
                                'qty' => $qty
                            ]);

                        if ($qty_to_update <= 0)
                            break;
                    }

                    if ($qty_to_update > 0) { // Check the quantity medicine to sale is fulfilled
                        DB::rollBack();

                        throw ValidationException::withMessages([
                            'transaction_patient.'.$key.'.quantity' => 'The Quantity field not have balance '
                        ]);
                    }
                }
            }

            DB::commit();

            return to_route('transactions.patients.index')->with('success', 'Transaction Patient has been updated.');
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error($e->getTraceAsString());

            throw $e;
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $transactionPatient = TransactionPatient::query()
                ->with([
                    'transactionPatientHasMedicines',
                    'transactionPatientHasMedicines.medicineSales',
                ])
                ->findOrFail($id);

            foreach ($transactionPatient->transactionPatientHasMedicines as $transactionPatientHasMedicine) {
                foreach ($transactionPatientHasMedicine->medicineSales as $medicineSale) {
                    $transactionMedicine = TransactionMedicine::query()
                        ->where('id', $medicineSale->transaction_medicine_id)
                        ->withTrashed()
                        ->first();

                    $transactionMedicine->increment('qty_balance', $medicineSale->qty);

                    MedicineSale::query()->where('id', $medicineSale->id)->delete();
                }
            }

            TransactionPatientHasMedicine::query()->where('transaction_patient_id', $transactionPatient->id)->delete();

            $transactionPatient->delete();

            DB::commit();

            return to_route('transactions.patients.index')->with('success', 'Transaction Patient has been deleted.');
        } catch (QueryException $e) {
            DB::rollBack();

            Log::error($e->getTraceAsString());

            throw $e;
        }
    }
}
