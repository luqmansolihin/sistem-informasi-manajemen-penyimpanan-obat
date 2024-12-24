@extends('layouts.app')

@section('content')
    <div class="row pt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Data Obat | Ubah</h3>
                </div>

                <form class="form-horizontal" action="{{ route('medicines.update', $medicine->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="name" class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       placeholder="Nama"
                                       value="{{ old('name', $medicine->name) }}"
                                       required>
                                @error('name')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="manufacture" class="col-sm-2 col-form-label">Produsen</label>
                            <div class="col-sm-6">
                                <input type="text"
                                       name="manufacture"
                                       class="form-control @error('manufacture') is-invalid @enderror"
                                       id="manufacture"
                                       placeholder="Produsen"
                                       value="{{ old('manufacture', $medicine->manufacture) }}"
                                       required>
                                @error('manufacture')
                                    <span class="error invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
