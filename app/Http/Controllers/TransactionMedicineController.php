<?php

namespace App\Http\Controllers;


use App\Http\Requests\TransactionMedicineStoreRequest;
use App\Http\Requests\TransactionMedicineUpdateRequest;
use App\Models\Medicine;
use App\Models\MedicineSale;
use App\Models\TransactionMedicine;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class TransactionMedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $transactionMedicines = TransactionMedicine::query()
            ->with('medicine:id,name')
            ->select(['id', 'medicine_id', 'purchase_date', 'expired_date', 'qty', 'qty_balance'])
            ->get();

        return view('pages.transactions.medicines.index', compact('transactionMedicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $medicines = Medicine::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('pages.transactions.medicines.create', compact('medicines'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TransactionMedicineStoreRequest $request): RedirectResponse
    {
        TransactionMedicine::query()
            ->create($request->safe()->except(['medicine', 'quantity']));

        return to_route('transactions.medicines.index')->with('success', 'Transaction Medicine has been created.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $transactionMedicine = TransactionMedicine::query()->findOrFail($id);

        $medicines = Medicine::query()
            ->select(['id', 'name'])
            ->orderBy('name')
            ->get();

        return view('pages.transactions.medicines.edit', compact('transactionMedicine', 'medicines'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TransactionMedicineUpdateRequest $request, int $id): RedirectResponse
    {
        $transactionMedicine = TransactionMedicine::query()->findOrFail($id);

        $transactionMedicineUpdate = $request->safe()->except(['medicine', 'quantity']);
        $transactionMedicineUpdate['qty_balance'] = $transactionMedicine->qty_balance;

        if ($transactionMedicine->medicine_id == $request->validated('medicine_id')) {
            $isExistMedicineSale = MedicineSale::query()
                ->where('transaction_medicine_id', $transactionMedicine->id)
                ->exists();

            if ($isExistMedicineSale) {
                Validator::validate(['medicine' => $request->validated('medicine')],
                    ['medicine' => 'not_in:' . $request->validated('medicine')]);
            }
        }

        $qtyUpdate = $request->validated('qty');

        if ($request->validated('qty') < $transactionMedicine->qty) {
            $sumQtyMedicineSale = MedicineSale::query()
                ->where('transaction_medicine_id', $transactionMedicine->id)
                ->sum('qty');

            if ($qtyUpdate < $sumQtyMedicineSale) {
                Validator::validate(['quantity' => $qtyUpdate], ['quantity' => 'int|min:' . $sumQtyMedicineSale]);
            }

            $transactionMedicineUpdate['qty_balance'] = $transactionMedicine->qty_balance - ($transactionMedicine->qty - $qtyUpdate);
        }

        if ($request->validated('qty') > $transactionMedicine->qty) {
            $transactionMedicineUpdate['qty_balance'] = $transactionMedicine->qty_balance + ($qtyUpdate - $transactionMedicine->qty);
        }

        $transactionMedicine->update($transactionMedicineUpdate);

        return to_route('transactions.medicines.index')->with('success', 'Transaction Medicine has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $transactionMedicine = TransactionMedicine::query()->findOrFail($id);

        $isExistMedicineSale = MedicineSale::query()
            ->where('transaction_medicine_id', $transactionMedicine->id)
            ->exists();

        if ($isExistMedicineSale) {
            return to_route('transactions.medicines.index')->with('error', 'Transaction Medicine is sold, failed to delete.');
        }

        $transactionMedicine->delete();

        return to_route('transactions.medicines.index')->with('success', 'Transaction Medicine has been deleted.');
    }
}
