@extends('layouts.admin.mastertemplateadmin')
@section('content')
    <div class="page-header">
        <h3 class="page-title"> Konfigurasi Lokasi </h3>
    </div>
    <div class="row">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form action="/lokasi/store" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if (Session::get('error'))
                                    <div class="alert alert-danger">
                                        {{ Session::get('error') }}
                                    </div>
                                @endif

                                <div class="form-group">
                                    <input type="text" value="{{ $ceklokasi != null ? $ceklokasi->lokasi : '' }}"
                                        class="form-control" name="lokasi" placeholder="Lokasi ( Latitude, Longitude)"
                                        required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <button class="btn btn-primary w-100">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
