@extends('layouts.admin.mastertemplateadmin')

@section('content')
    <div class="page-header">
        <h3 class="page-title"> Monitoring Presensi </h3>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <input type="text" name="tanggal" class="form-control" id="tanggal">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>NIK</th>
                                        <th>Nama Karyawan</th>
                                        <th>Foto</th>
                                        <th>Jam Masuk</th>
                                        <th>Foto</th>
                                        <th>Jam Pulang</th>
                                        <th>Lokasi</th>
                                    </tr>
                                </thead>
                                <tbody id="showpresensi"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#tanggal").daterangepicker({
                singleDatePicker: true,
                autoApply: true,
                locale: {
                    format: 'YYYY-MM-DD',
                }
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
