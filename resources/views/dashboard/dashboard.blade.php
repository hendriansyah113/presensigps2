@extends('layouts.presensi')
@section('content')
    <!-- App Capsule -->
    <style>
        .logout {
            position: absolute;
            color: white;
            font-size: 30px;
            text-decoration: none;
            right: 8px;
        }

        .logout:hover {
            color: white;
        }
    </style>
    <div id="appCapsule">
        <div class="section" id="user-section">
            <a href="/proseslogout" class="logout">
                <ion-icon name="exit-outline"></ion-icon>
            </a>
            <div id="user-detail">
                <div class="avatar">
                    @if (!empty(Auth::guard('karyawan')->user()->foto))
                        @php
                            $path = Storage::url('uploads/karyawan/' . Auth::guard('karyawan')->user()->foto);
                        @endphp
                        <img src="{{ url($path) }}" alt="avatar" class="imaged w64 rounded"
                            style="height:60px; object-fit: cover;">
                    @else
                        <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                    @endif
                </div>
                <div id="user-info">
                    <h2 id="user-name">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h2>
                    <span id="user-role">{{ Auth::guard('karyawan')->user()->jabatan }}</span>
                </div>
            </div>
        </div>
        <div class="section mt-2" id="presence-section">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="todaypresence mt-2">
                <div class="row">
                    <div class="col-6">
                        <div class="card gradasigreen" style="height: 96px">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($presensihariini != null)
                                            @php
                                                $path = Storage::url('uploads/absensi/' . $presensihariini->foto_in);
                                            @endphp
                                            <img src="{{ url($path) }}" alt="" class="imaged w64">
                                        @else
                                            <ion-icon name="camera"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Masuk</h4>
                                        <span>{{ $presensihariini != null ? $presensihariini->jam_in : 'Belum Absen' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card gradasired" style="height: 96px">
                            <div class="card-body">
                                <div class="presencecontent">
                                    <div class="iconpresence">
                                        @if ($presensihariini != null && $presensihariini->jam_out != null)
                                            @php
                                                $path = Storage::url('uploads/absensi/' . $presensihariini->foto_out);
                                            @endphp
                                            <img src="{{ url($path) }}" alt="" class="imaged w64">
                                        @else
                                            <ion-icon name="camera"></ion-icon>
                                        @endif
                                    </div>
                                    <div class="presencedetail">
                                        <h4 class="presencetitle">Pulang</h4>
                                        <span>{{ $presensihariini != null && $presensihariini->jam_out != '00:00:00' ? $presensihariini->jam_out : 'Belum Absen' }}</span>
                                        {{-- <span>{{ $presensihariini->jam_out = '00:00:00' && $presensihariini->jam_out != null ? 'Belum Absen' : $presensihariini->jam_out }}</span> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="rekappresence">
                <h3>Rekap Presensi Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
                <div class="row">
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="16px 12px !important; line-height:0.8rem">
                                <span class="badge bg-danger"
                                    style="position: absolute; top:7px; right:12px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlhadir }}</span>
                                <ion-icon name="accessibility-outline" style="font-size: 1.6rem;" class="text-primary mb-1">
                                </ion-icon>
                                <span style="font-size: 0.8rem; font-weight:500">Hadir</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="16px 12px !important; line-height:0.8rem">
                                <span class="badge bg-danger"
                                    style="position: absolute; top:7px; right:12px; font-size:0.6rem; z-index:999">{{ $izin->jmlizin }}</span>
                                <ion-icon name="newspaper-outline" style="font-size: 1.6rem;" class="text-success mb-1">
                                </ion-icon>
                                <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="16px 12px !important; line-height:0.8rem">
                                <span class="badge bg-danger"
                                    style="position: absolute; top:7px; right:12px; font-size:0.6rem; z-index:999">{{ $izin->jmlsakit }}</span>
                                <ion-icon name="medkit-outline" style="font-size: 1.6rem;" class="text-warning mb-1">
                                </ion-icon>
                                <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="card">
                            <div class="card-body text-center" style="16px 12px !important; line-height:0.8rem">
                                <span class="badge bg-danger"
                                    style="position: absolute; top:7px; right:12px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlterlambat }}</span>
                                <ion-icon name="alarm-outline" style="font-size: 1.6rem;" class="text-danger mb-1">
                                </ion-icon>
                                <span style="font-size: 0.8rem; font-weight:500">Telat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="presencetab mt-2">
                <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                    <ul class="nav nav-tabs style1" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                                Bulan Ini
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                                Leaderboard
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-2" style="margin-bottom:100px;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($historibulanini as $d)
                                <li>
                                    <div class="item">
                                        <div class="icon-box bg-primary">
                                            <ion-icon name="finger-print-outline"></ion-icon>
                                        </div>
                                        <div class="in">
                                            <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                                            <span
                                                class="badge {{ $d->jam_in < '08:00' ? 'bg-success' : 'bg-danger' }}">{{ date('H:i', strtotime($d->jam_in)) }}</span>
                                            <span
                                                class="badge badge-danger">{{ $d->jam_out != '00:00:00' ? date('H:i', strtotime($d->jam_out)) : 'Belum Absen' }}</span>
                                            {{-- <span
                                                class="badge badge-danger">{{ $presensihariini != null && $presensihariini->jam_out != null ? $d->jam_out : 'Belum Absen' }}</span> --}}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel">
                        <ul class="listview image-listview">
                            @foreach ($leaderboard as $d)
                                <li>
                                    <div class="item">
                                        @if (!empty($d->foto))
                                            @php
                                                $path = Storage::url('uploads/karyawan/' . $d->foto);
                                            @endphp
                                            <img src="{{ url($path) }}" alt="image" class="image">
                                        @else
                                            <img src="assets/img/sample/avatar/avatar1.jpg" alt="image"
                                                class="image">
                                        @endif

                                        <div class="in">
                                            <div><b>{{ $d->nama_lengkap }}</b><br>
                                                <small class="text-muted">{{ $d->jabatan }}</small>
                                            </div>
                                            <span
                                                class="badge {{ $d->jam_in < '08:00' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_in }}</span>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection
