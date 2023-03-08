@extends('layouts.admin.mastertemplateadmin')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        Monitoring Presensi
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-icon mb-3">
                                        <span class="input-icon-addon">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/user -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-calendar" width="40" height="40"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path
                                                    d="M4 5m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                                                </path>
                                                <path d="M16 3l0 4"></path>
                                                <path d="M8 3l0 4"></path>
                                                <path d="M4 11l16 0"></path>
                                                <path d="M11 15l1 0"></path>
                                                <path d="M12 15l0 3"></path>
                                            </svg>
                                        </span>
                                        <input type="text" name="tanggal" class="form-control" autocomplete="off"
                                            placeholder="Tanggal.." id="tanggal">
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>NIK</th>
                                                <th>Nama Karyawan</th>
                                                <th>Departemen</th>
                                                <th>Jam Masuk</th>
                                                <th>Foto</th>
                                                <th>Jam Pulang</th>
                                                <th>Foto</th>
                                                <th>Keterangan</th>
                                                <th>Lokasi</th>
                                            </tr>
                                        </thead>
                                </div>
                            </div>
                            <tbody id="showpresensi"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-blur fade" id="mdlshowmap" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lokasi Absen</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="loadmap">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(function() {
            $("#tanggal").datepicker({
                autoclose: true,
                todayHighlight: true,
                format: "yyyy-mm-dd"
            });

            $("#tanggal").change(function(e) {
                showpresensi();
            });

            function showpresensi() {
                var tanggal = $("#tanggal").val();
                $.ajax({
                    type: "POST",
                    url: "/monitoring/show",
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function(respond) {
                        $("#showpresensi").html(respond);
                    }
                });
            }

            showpresensi();
        });
    </script>
@endpush
