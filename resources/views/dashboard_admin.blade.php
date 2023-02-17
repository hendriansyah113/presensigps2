@extends('layouts.admin.mastertemplateadmin')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-home"></i>
            </span> Dashboard
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>
    <div class="row">
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets-purple/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image">
                    <h4 class="font-weight-normal mb-3">Data Karyawan <i class="mdi mdi-account mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $jmlkaryawan }}</h2>
                    <h6 class="card-text">Data Jumlah Karyawan</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-info card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets-purple/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image">
                    <h4 class="font-weight-normal mb-3">Jumlah Hadir Hari Ini <i
                            class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $jmlhadir }}</h2>
                    <h6 class="card-text">Jumlah Karyawan Hadir Hari ini</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-success card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets-purple/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image">
                    <h4 class="font-weight-normal mb-3">Jumlah Karyawan Izin / Sakit <i
                            class="mdi mdi-diamond mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $jmlizin }}</h2>
                    <h6 class="card-text">Jumlah Karyawan Izin / Sakit Hari Ini</h6>
                </div>
            </div>
        </div>
        <div class="col-md-3 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
                <div class="card-body">
                    <img src="{{ asset('assets-purple/images/dashboard/circle.svg') }}" class="card-img-absolute"
                        alt="circle-image">
                    <h4 class="font-weight-normal mb-3">Jumlah Karyawan Terlambat <i
                            class="mdi mdi-calendar-clock mdi-24px float-right"></i>
                    </h4>
                    <h2 class="mb-5">{{ $jmlterlambat }}</h2>
                    <h6 class="card-text">Jumlah Karyawan Terlambat Hari Ini</h6>
                </div>
            </div>
        </div>
    </div>
@endsection
