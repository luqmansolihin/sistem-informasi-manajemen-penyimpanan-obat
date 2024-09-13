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
                    <h3 class="card-title">Transaction Medicine | Update</h3>
                </div>

                <form class="form-horizontal" action="{{ route('transactions.medicines.update', $transactionMedicine->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="medicine-id" class="col-sm-2 col-form-label">Medicine</label>
                            <div class="col-sm-6">
                                <select class="form-control select2 @error('medicine') is-invalid @enderror"
                                        id="medicine-id"
                                        name="medicine"
                                        required>
                                    @foreach($medicines as $medicine)
                                        <option value="{{ $medicine->id }}" @if (old('medicine', $transactionMedicine->medicine_id) == $medicine->id) selected @endif>{{ $medicine->name }}</option>
                                    @endforeach
                                </select>
                                @error('medicine')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       inputmode="numeric"
                                       name="quantity"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       placeholder="Quantity"
                                       value="{{ old('quantity', $transactionMedicine->qty) }}"
                                       pattern="[1-9][0-9]*"
                                       oninput="this.value = this.value.replace(/[^0-9]/g, ''); if (this.value < 1) this.value = '';"
                                       required>
                                @error('quantity')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="purchase-date" class="col-sm-2 col-form-label">Purchase Date</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="purchase-date" data-target-input="nearest">
                                    <input type="text"
                                           name="purchase_date"
                                           class="form-control datetimepicker-input @error('purchase_date') is-invalid @enderror"
                                           placeholder="Purchase Date"
                                           value="{{ old('purchase_date', $transactionMedicine->purchase_date) }}"
                                           data-target="#purchase-date"
                                           required>
                                    <div class="input-group-append" data-target="#purchase-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    @error('purchase_date')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="expired-date" class="col-sm-2 col-form-label">Expired Date</label>
                            <div class="col-sm-6">
                                <div class="input-group date" id="expired-date" data-target-input="nearest">
                                    <input type="text"
                                           name="expired_date"
                                           class="form-control datetimepicker-input @error('expired_date') is-invalid @enderror"
                                           placeholder="Expired Date"
                                           value="{{ old('expired_date', $transactionMedicine->expired_date) }}"
                                           data-target="#expired-date"
                                           required>
                                    <div class="input-group-append" data-target="#expired-date" data-toggle="datetimepicker">
                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                    </div>
                                    @error('expired_date')
                                        <span class="error invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="{{ url()->previous() }}" class="btn btn-warning">Cancel</a>
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
            $('.select2').select2()

            $('#purchase-date').datetimepicker({
                format: 'Y-MM-DD'
            })

            $('#expired-date').datetimepicker({
                format: 'Y-MM-DD'
            })
        })
    </script>
@endpush
