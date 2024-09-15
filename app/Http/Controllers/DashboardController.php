<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Patient;
use App\Models\TransactionMedicine;
use App\Models\TransactionPatient;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $medicineCount = Medicine::query()->count();
        $patientCount = Patient::query()->count();
        $transactionPatientCount = TransactionPatient::query()->count();

        $almostExpiredMedicines = TransactionMedicine::query()
            ->with('medicine')
            ->select(['medicine_id', 'purchase_date', 'expired_date', 'qty_balance'])
            ->whereDate('expired_date', '>=', now())
            ->whereDate('expired_date', '<=', now()->addDays(30))
            ->where('qty_balance', '>', 0)
            ->orderBy('expired_date')
            ->get();

        $latestPatientVisits = TransactionPatient::query()
            ->with('patient')
            ->select(['patient_id', 'checkup_date', 'disease_name'])
            ->whereDate('checkup_date', '<=', now())
            ->whereDate('checkup_date', '>=', now()->subDays(30))
            ->orderByDesc('checkup_date')
            ->get();

        $mostFrequentlySoldMedicines = Medicine::query()
            ->join('transaction_medicines', 'medicines.id', '=', 'transaction_medicines.medicine_id')
            ->join('medicine_sales', 'transaction_medicines.id', '=', 'medicine_sales.transaction_medicine_id')
            ->select('medicines.id', 'medicines.name', DB::raw('SUM(medicine_sales.qty) as total_qty_sold'))
            ->groupBy('medicines.id', 'medicines.name')
            ->orderBy('total_qty_sold', 'desc')
            ->take(100)
            ->get();

        $medicinesWithLowStock = Medicine::query()
            ->join('transaction_medicines', 'medicines.id', '=', 'transaction_medicines.medicine_id')
            ->select('medicines.id', 'medicines.name', DB::raw('SUM(transaction_medicines.qty_balance) as total_qty_balance'))
            ->whereNull('transaction_medicines.deleted_at')
            ->groupBy('medicines.id', 'medicines.name')
            ->having('total_qty_balance', '<', 10)
            ->get();

        return view('pages.dashboard', compact(
            'medicineCount',
            'patientCount',
            'transactionPatientCount',
            'almostExpiredMedicines',
            'latestPatientVisits',
            'mostFrequentlySoldMedicines',
            'medicinesWithLowStock',
        ));
    }
}
