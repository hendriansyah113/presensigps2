@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="/dashboard" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Izin / Sakit</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->
@endsection

@section('content')
    <div class="row" style="margin-top: 70px">
        <div class="col">
            <h4>Histori Izin / Sakit</h4>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-outline-success">
                    {{ $messagesuccess }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-outline-danger">
                    {{ $messageerror }}
                </div>
            @endif
            <ul class="listview image-listview">
                @foreach ($izin as $d)
                    <li>
                        <div class="item">
                            <div class="in">
                                <div>
                                    <b>{{ date('d-m-Y', strtotime($d->tanggal)) }}
                                        ({{ $d->status == 's' ? 'Sakit' : 'Izin' }})
                                    </b><br>
                                    <small class="text-muted">{{ $d->keterangan }}</small>
                                </div>
                                <span
                                    class="badge {{ $d->status_approve == 0 ? 'bg-primary' : ($d->status_approve == 1 ? 'bg-success' : 'bg-danger') }}">
                                    {{ $d->status_approve == 0 ? 'Waiting' : ($d->status_approve == 1 ? 'Approved' : 'Rejected') }}
                                </span>

                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
            <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
                <a href="/presensi/create_izin" class="fab" data-bs-toggle="dropdown">
                    <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
                </a>
            </div>
        </div>
    </div>
@endsection
