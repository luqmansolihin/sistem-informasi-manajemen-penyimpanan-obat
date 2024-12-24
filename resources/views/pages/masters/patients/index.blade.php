@extends('layouts.app')

@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endpush

@section('content')
    <div class="row pt-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Daftar Data Pasien</h3>
                </div>

                <div class="card-body">
                    @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>{{ session()->get('success') }}</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                    @can('MasterPatient.create')
                        <div class="col-lg-2 p-0 mb-2">
                            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-block">Tambah Pasien</a>
                        </div>
                    @endcan

                    <table id="patients" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Alamat</th>
                            @canany(['MasterPatient.update', 'MasterPatient.delete'])
                                <th>Aksi</th>
                            @endcanany
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->name }}</td>
                                <td>{{ $patient->address }}</td>
                                @canany(['MasterPatient.update', 'MasterPatient.delete'])
                                    <td>
                                        @can('MasterPatient.update')
                                            <a href="{{ route('patients.edit', $patient->id) }}" class="badge bg-warning" title="Ubah"><i class="fas fa-edit"></i></a>
                                        @endcan
                                        @can('MasterPatient.delete')
                                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="d-inline">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="badge bg-danger border-0" title="Hapus" onclick="return confirm('Anda yakin untuk menghapus?')">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                @endcanany
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <script>
        $(function () {
            $("#patients").DataTable({
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": [
                    {
                        extend: 'print',
                        text: 'Cetak',
                        title: '',
                        customize: function (win) {
                            let searchValue = $(".dataTables_filter input").val();

                            // Tambahkan sub-header
                            $(win.document.body).prepend(
                                '<div id="custom-header" style="text-align: center;">' +
                                '<h2>' + "{{ config('app.name') }}" + '</h2>' +
                                '<h3>Data Pasien</h3>' +
                                '</div>'
                            );

                            // Tambahkan informasi pencarian setelah tanggal dicetak jika ada pencarian
                            if (searchValue) {
                                $(win.document.body).find('#custom-header').append(
                                    '<p style="text-align: center;">Pencarian: ' + searchValue.toUpperCase() + '</p>'
                                );
                            }
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        title: '',
                        customize: function (doc) {
                            let searchValue = $(".dataTables_filter input").val();

                            // Tambahkan sub-header
                            doc.content.splice(0, 0, {
                                alignment: 'center',
                                text: [
                                    { text: "{{ config('app.name') }}\n", fontSize: 16, bold: true },
                                    { text: "Data Pasien\n", fontSize: 14 },
                                ]
                            });

                            // Tambahkan informasi pencarian jika ada
                            if (searchValue) {
                                doc.content.splice(1, 0, {
                                    alignment: 'center',
                                    text: 'Pencarian: ' + searchValue.toUpperCase(),
                                    margin: [0, 10]
                                });
                            }
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excelHtml5',
                        title: "{{ config('app.name') }}",  // This will be the title for the Excel file
                        messageTop: function() {
                            let searchValue = $(".dataTables_filter input").val();
                            let searchText = searchValue ? `Pencarian: ${searchValue.toUpperCase()}` : '';

                            return `Data Pasien`+
                                ` \n${searchText}`;
                        },
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'colvis',
                        text: 'Visibilitas Kolom'
                    }
                ],
                "language": {
                    search: "Cari:",
                    info: "Menampilkan _START_ hingga _END_ dari _TOTAL_ entri",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                }
            })
                .buttons()
                .container()
                .appendTo('#patients_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
