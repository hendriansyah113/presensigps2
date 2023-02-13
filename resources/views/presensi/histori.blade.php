@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Histori Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
    <style>
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <div class="form-group basic">
                <div class="input-wrapper">
                    <select id="bulan" class="form-control form-select">
                        <option value="">Pilih Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}">{{ $bulan[$i] }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group basic">
                <div class="input-wrapper">
                    <select id="tahun" class="form-control form-select">
                        <option value="">Tahun</option>
                        @php
                            $tahunmulai = 2022;
                            $tahunini = date('Y');
                        @endphp

                        @for ($thn = $tahunmulai; $thn <= $tahunini; $thn++)
                            <option value="{{ $thn }}">{{ $thn }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="form-group">
                <button id="tampilkan" class="btn btn-primary btn-block">
                    <ion-icon name="desktop-outline"></ion-icon>
                    Tampilkan
                </button>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col text-center" id="tampilhistori">
            <div class="spinner-border text-primary" id="loading"></div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            $("#loading").hide();
            $("#tampilkan").click(function(e) {
                e.preventDefault();
                var bulan = $("#bulan").val();
                var tahun = $("#tahun").val();
                if (bulan == "") {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Silahkan Pilih Bulan',
                        icon: 'warning',
                    })
                } else if (tahun == "") {
                    Swal.fire({
                        title: 'Gagal!',
                        text: 'Silahkan Pilih Tahun',
                        icon: 'warning',
                    })
                } else {
                    $("#loading").show();
                    $.ajax({
                        type: 'POST',
                        url: '/presensi/gethistori',
                        data: {
                            _token: "{{ csrf_token() }}",
                            bulan: bulan,
                            tahun: tahun
                        },
                        cache: false,
                        success: function(respond) {
                            $("#loading").hide();
                            $("#tampilhistori").html(respond);
                        }
                    });
                }
            });
        });
    </script>
@endpush
