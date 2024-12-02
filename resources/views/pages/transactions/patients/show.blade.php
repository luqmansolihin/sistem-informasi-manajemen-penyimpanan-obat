@extends('layouts.app')

@section('content')
    <div class="row pt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaksi Pasien | @if(request()->is('transactions/patients/*/delete')) Hapus @else Detail @endif</h3>
                </div>

                <form class="form-horizontal">
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="patient-id" class="col-sm-2 col-form-label">Pasien</label>
                            <div class="col-sm-6">
                                <select class="form-control select2"
                                        id="patient-id"
                                        name="patient"
                                        disabled>
                                    <option value="{{ $transactionPatient->patient_id }}" selected>{{ $transactionPatient->patient->name. ' - ' .$transactionPatient->patient->address }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="checkup-date" class="col-sm-2 col-form-label">Tanggal Pemeriksaan</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="checkup-date" data-target-input="nearest">
                                    <input type="text"
                                           name="checkup_date"
                                           class="form-control datetimepicker-input"
                                           placeholder="Tanggal Pemeriksaan"
                                           value="{{ date('Y-m-d', strtotime($transactionPatient->checkup_date)) }}"
                                           data-target="#checkup-date"
                                           disabled>
                                    <div class="input-group-append" data-target="#checkup-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="disease_name" class="col-sm-2 col-form-label">Diagnosa Penyakit</label>
                            <div class="col-sm-6">
                                <textarea
                                    name="disease_name"
                                    class="form-control"
                                    id="disease_name"
                                    rows="3"
                                    placeholder="Diagnosa Penyakit"
                                    disabled>{{ $transactionPatient->disease_name }}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="medical_expense" class="col-sm-2 col-form-label">Biaya Pemeriksaan</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fa fa-rupiah-sign"></i>
                                        </span>
                                    </div>
                                    <input type="text"
                                           inputmode="numeric"
                                           name="medical_expense"
                                           class="form-control float-right"
                                           id="medical_expense"
                                           placeholder="Biaya Pemeriksaan"
                                           value="{{ $transactionPatient->medical_expense }}"
                                           pattern="[1-9][0-9]*"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                           disabled>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Obat</label>
                            </div>
                            <div class="col-sm-4">
                                <label>Kuantitaas</label>
                            </div>
                        </div>
                        <div class="form_field_outer">
                            @foreach($transactionPatient->transactionPatientHasMedicines as $transactionPatientHasMedicine)
                                <div class="form-group row form_field_outer_row">
                                    <div class="col-sm-4 mb-1">
                                        <select class="form-control select2"
                                                id="medicine-id-0"
                                                name="transaction_patient[0][medicine]"
                                                disabled>
                                            <option value="{{ $transactionPatientHasMedicine->medicine_id }}" selected>
                                                {{ $transactionPatientHasMedicine->medicine->name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-sm-4 mb-1">
                                        <input type="text"
                                               inputmode="numeric"
                                               name="transaction_patient[0][quantity]"
                                               class="form-control"
                                               id="quantity-0"
                                               placeholder="Kuantitas"
                                               value="{{ $transactionPatientHasMedicine->qty }}"
                                               pattern="[1-9][0-9]*"
                                               oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                               disabled>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </form>
                <div class="card-footer">
                    @if(request()->is('transactions/patients/*/delete'))
                        <form action="{{ route('transactions.patients.destroy', $transactionPatient->id) }}" method="POST" class="d-inline">
                            @method('delete')
                            @csrf
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Anda yakin untuk menghapus?')">
                                Hapus
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('transactions.patients.index') }}" class="btn btn-warning">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
