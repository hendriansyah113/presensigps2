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
            <form action="/presensi/store_izin" method="post" enctype="multipart/form-data" id="frmIzin">
                @csrf
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <input type="date" name="tanggal" id="tanggal" class="form-control" placeholder="Tanggal"
                            autocomplete="off">
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                            </ion-icon>
                        </i>
                    </div>
                </div>
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <select id="status" class="form-control form-select" name="status">
                            <option value="">Izin / Sakit</option>
                            <option value="i">Izin</option>
                            <option value="s">Sakit</option>
                        </select>
                    </div>
                </div>
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <textarea name="keterangan" id="keterangan" rows="3" class="form-control" placeholder="Keterangan"></textarea>
                        <i class="clear-input">
                            <ion-icon name="close-circle" role="img" class="md hydrated" aria-label="close circle">
                            </ion-icon>
                        </i>
                    </div>
                </div>
                {{-- <div class="custom-file-upload" id="fileUpload1">
                    <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg, .pdf">
                    <label for="fileuploadInput">
                        <span>
                            <strong>
                                <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                    aria-label="cloud upload outline"></ion-icon><i>Tap to Upload</i>
                            </strong>
                        </span>
                    </label>
                </div> --}}
                <div class="form-group boxed">
                    <div class="input-wrapper">
                        <button type="submit" class="btn btn-primary btn-block">
                            <ion-icon name="send-outline"></ion-icon>Send
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('myscript')
    <script>
        $(document).ready(function() {
            $("#tanggal").change(function(e) {
                var tanggal = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/presensi/cekpengajuanizin',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tanggal: tanggal
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond == 1) {
                            Swal.fire({
                                title: 'Oops !',
                                text: 'Anda Sudah Input Pengajuan Pada Tanggal Tersebut !',
                                icon: 'warning'
                            }).then((result) => {
                                $("#tanggal").val("");
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
