@extends('layouts.admin.mastertemplateadmin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-file-outline"></i>
            </span> Laporan Presensi
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span> <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="col-md-6 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Laporan Presensi</h4>
                <p class="card-description"> Laporan Presensi </p>
                <form class="forms-sample" id="frmLaporan" method="POST" action="/presensi/laporan/cetak" target="_blank">
                    @csrf
                    <div class="form-group">
                        <select name="bulan" id="bulan" class="form-control">
                            <option value="">Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">{{ $bulan[$i] }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group">
                        <select name="tahun" id="tahun" class="form-control">
                            <option value="">Tahun</option>
                            @php
                                $tahunmulai = 2022;
                                $tahunskrg = date('Y');
                            @endphp
                            @for ($thn = $tahunmulai; $thn <= $tahunskrg; $thn++)
                                <option value="{{ $thn }}">{{ $thn }}</option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gradient-primary me-2">
                        <i class="mdi mdi-printer"></i> Cetak</button>
                    <button class="btn btn-light">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#frmLaporan").submit(function() {
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                if (bulan == "") {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Bulan Harus Diisi!',
                        icon: 'warning',
                        confirmButtonText: 'ok'
                    })
                    return false;
                } else if (tahun == "") {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Tahun Harus Diisi!',
                        icon: 'warning',
                        confirmButtonText: 'ok'
                    })
                    return false;
                } else {
                    return true;
                }
            });
        });
    </script>
@endpush
