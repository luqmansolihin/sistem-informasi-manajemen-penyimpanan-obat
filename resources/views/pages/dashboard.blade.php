@extends('layouts.app')

@push('script')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@endpush

@section('content')
    <div class="row pt-3">
        <div class="col-lg-4">
            <div class="small-box bg-cyan">
                <div class="inner">
                    <h3>{{ $medicineCount }}</h3>
                    <p>Total Medicine Available</p>
                </div>
                <div class="icon">
                    <i class="ion ion-medkit"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="small-box bg-cyan">
                <div class="inner">
                    <h3>{{ $patientCount }}</h3>
                    <p>Total Patient Registered</p>
                </div>
                <div class="icon">
                    <i class="ion ion-person"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="small-box bg-cyan">
                <div class="inner">
                    <h3>{{ $transactionPatientCount }}</h3>
                    <p>Total Patient Visits</p>
                </div>
                <div class="icon">
                    <i class="ion ion-android-walk"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Almost Expired Medicines</h3>
                </div>

                <div class="card-body table-responsive p-0" style="height: calc(100vh - 70vh);">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Purchase Date</th>
                            <th>Expired Date</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($almostExpiredMedicines as $almostExpiredMedicine)
                                <tr>
                                    <td>{{ $almostExpiredMedicine->medicine->name }}</td>
                                    <td>{{ date('d F Y', strtotime($almostExpiredMedicine->purchase_date)) }}</td>
                                    <td>{{ date('d F Y', strtotime($almostExpiredMedicine->expired_date)) }}</td>
                                    <td>{{ $almostExpiredMedicine->qty_balance }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Medicine with Low Stock</h3>
                </div>

                <div class="card-body table-responsive p-0" style="height: calc(100vh - 70vh);">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Quantity</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($medicinesWithLowStock as $medicineWithLowStock)
                            <tr>
                                <td>{{ $medicineWithLowStock->name }}</td>
                                <td>{{ $medicineWithLowStock->total_qty_balance }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Most Frequently Sold Medicine</h3>
                </div>

                <div class="card-body table-responsive p-0" style="height: calc(100vh - 70vh);">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>Medicine Name</th>
                            <th>Total Quantity Sold</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($mostFrequentlySoldMedicines as $mostFrequentlySoldMedicine)
                            <tr>
                                <td>{{ $mostFrequentlySoldMedicine->name }}</td>
                                <td>{{ $mostFrequentlySoldMedicine->total_qty_sold }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-cyan">
                    <h3 class="card-title">Latest Patient Visits</h3>
                </div>

                <div class="card-body table-responsive p-0" style="height: calc(100vh - 70vh);">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Address</th>
                            <th>Checkup Date</th>
                            <th>Disease Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($latestPatientVisits as $latestPatientVisit)
                            <tr>
                                <td>{{ $latestPatientVisit->patient->name }}</td>
                                <td>{{ $latestPatientVisit->patient->address }}</td>
                                <td>{{ date('d F Y', strtotime($latestPatientVisit->checkup_date)) }}</td>
                                <td>{{ $latestPatientVisit->disease_name }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
