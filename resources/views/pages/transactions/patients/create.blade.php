@extends('layouts.app')

@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endpush

@section('content')
    <div class="row pt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Transaction Patient | Create</h3>
                </div>

                <form class="form-horizontal" action="{{ route('transactions.patients.store') }}" method="POST">
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="patient-id" class="col-sm-2 col-form-label">Patient</label>
                            <div class="col-sm-6">
                                <select class="form-control select2 @error('patient') is-invalid @enderror"
                                        id="patient-id"
                                        name="patient"
                                        required>
                                    @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" @if (old('patient') == $patient->id) selected @endif>{{ $patient->name }}</option>
                                    @endforeach
                                </select>
                                @error('patient')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="checkup-date" class="col-sm-2 col-form-label">Checkup Date</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="checkup-date" data-target-input="nearest">
                                    <input type="text"
                                           name="checkup_date"
                                           class="form-control datetimepicker-input @error('checkup_date') is-invalid @enderror"
                                           placeholder="Checkup Date"
                                           value="{{ old('checkup_date') }}"
                                           data-target="#checkup-date"
                                           required>
                                    <div class="input-group-append" data-target="#checkup-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    @error('checkup_date')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="disease_name" class="col-sm-2 col-form-label">Disease Name</label>
                            <div class="col-sm-6">
                                <textarea
                                    name="disease_name"
                                    class="form-control @error('disease_name') is-invalid @enderror"
                                    id="disease_name"
                                    rows="3"
                                    placeholder="Disease Name"
                                    required>{{ old('disease_name') }}</textarea>
                                @error('disease_name')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="medical_expense" class="col-sm-2 col-form-label">Medical Expense</label>
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
                                           class="form-control float-right @error('medical_expense') is-invalid @enderror"
                                           id="medical_expense"
                                           placeholder="Medical Expense"
                                           value="{{ old('medical_expense') }}"
                                           pattern="[1-9][0-9]*"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                           required>
                                    @error('medical_expense')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <label>Medicine</label>
                            </div>
                            <div class="col-sm-4">
                                <label>Quantity</label>
                            </div>
                        </div>
                        <div class="form_field_outer">
                            <div class="form-group row form_field_outer_row">
                                <div class="col-sm-4 mb-1">
                                    <select class="form-control select2 @error('transaction_patient.0.medicine') is-invalid @enderror"
                                            id="medicine-id-0"
                                            name="transaction_patient[0][medicine]"
                                            required>
                                        @foreach($medicines as $medicine)
                                            <option value="{{ $medicine->id }}"
                                                    @if ((old('transaction_patient') ? old('transaction_patient')[0]['medicine'] : '') == $medicine->id) selected @endif>
                                                {{ $medicine->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('transaction_patient.0.medicine')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-sm-4 mb-1">
                                    <input type="text"
                                           inputmode="numeric"
                                           name="transaction_patient[0][quantity]"
                                           class="form-control @error('transaction_patient.0.quantity') is-invalid @enderror"
                                           id="quantity-0"
                                           placeholder="Quantity"
                                           value="{{ old('transaction_patient') ? old('transaction_patient')[0]['quantity'] : '' }}"
                                           pattern="[1-9][0-9]*"
                                           oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                           required>
                                    @error('transaction_patient.0.quantity')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col mb-1">
                                    <button type="button" class="btn btn-danger remove_node_btn_frm_field" disabled>
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <button type="button" class="btn btn-primary add_new_frm_field_btn">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ route('transactions.patients.index') }}" class="btn btn-warning">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            let body = $('body')
            let form_field_outer = $('.form_field_outer')

            $('#checkup-date').datetimepicker({
                format: 'Y-MM-DD'
            })

            body.on('click', '.add_new_frm_field_btn', function (){
                let index = form_field_outer.find('.form_field_outer_row').length

                form_field_outer.append(`
                    <div class="form-group row form_field_outer_row">
                        <div class="col-sm-4 mb-2">
                            <select class="form-control select2"
                                    id="medicine-id-${index}"
                                    name="transaction_patient[${index}][medicine]"
                                    required>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-4 mb-2">
                            <input type="text"
                                   inputmode="numeric"
                                   name="transaction_patient[${index}][quantity]"
                                   class="form-control"
                                   id="quantity-${index}"
                                   placeholder="Quantity"
                                   pattern="[1-9][0-9]*"
                                   oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                   required>
                        </div>
                        <div class="col mb-2">
                            <button type="button" class="btn btn-danger remove_node_btn_frm_field" disabled>
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                `)

                form_field_outer.find('.remove_node_btn_frm_field:not(:first)').prop('disabled', false)
                form_field_outer.find('.remove_node_btn_frm_field').first().prop('disabled', true)

                $('.select2').select2()
            })

            body.on('click', '.remove_node_btn_frm_field', function () {
                $(this).closest('.form_field_outer_row').remove()

                updateIndexes()
            })

            function updateIndexes() {
                $('.form_field_outer .form_field_outer_row').each(function(index) {
                    // Update ID dan NAME untuk setiap medicine
                    $(this).find('select[name^="transaction_patient["]').attr({
                        id: `medicine-id-${index}`,
                        name: `transaction_patient[${index}][medicine]`
                    })

                    // Update ID dan NAME untuk setiap quantity
                    $(this).find('input[name^="transaction_patient["]').attr({
                        id: `quantity-${index}`,
                        name: `transaction_patient[${index}][quantity]`
                    })

                    // Update label (optional, if you have labels targeting inputs by ID)
                    $(this).find('label[for^="medicine-id"]').attr('for', `medicine-id-${index}`)
                    $(this).find('label[for^="quantity"]').attr('for', `quantity-${index}`)
                });
            }

            // Initialize with existing fields if any
            @foreach (old('transaction_patient', []) as $index => $transaction)
                @if($loop->iteration != 1)
                    form_field_outer.append(`
                        <div class="form-group row form_field_outer_row">
                            <div class="col-sm-4 mb-2">
                                <select class="form-control select2 @error('transaction_patient.'.$index.'.medicine') is-invalid @enderror"
                                        id="medicine-id-{{$loop->index}}"
                                        name="transaction_patient[{{$loop->index}}][medicine]"
                                        required>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}" @if (old('transaction_patient')[$index]['medicine'] == $medicine->id) selected @endif>{{ $medicine->name }}</option>
                                    @endforeach
                                </select>
                                @error('transaction_patient.'.$index.'.medicine')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-sm-4 mb-2">
                                <input type="text"
                                       inputmode="numeric"
                                       name="transaction_patient[{{$loop->index}}][quantity]"
                                       class="form-control @error('transaction_patient.'.$index.'.quantity') is-invalid @enderror"
                                       id="quantity-{{$loop->index}}"
                                       placeholder="Quantity"
                                       value="{{ old('transaction_patient')[$index]['quantity'] }}"
                                       pattern="[1-9][0-9]*"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                       required>
                                @error('transaction_patient.'.$index.'.quantity')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col mb-2">
                                <button type="button" class="btn btn-danger remove_node_btn_frm_field" disabled>
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        </div>
                    `)

                    form_field_outer.find('.remove_node_btn_frm_field:not(:first)').prop('disabled', false)
                    form_field_outer.find('.remove_node_btn_frm_field').first().prop('disabled', true)
                @endif
            @endforeach

            $('.select2').select2()
        })
    </script>
@endpush
