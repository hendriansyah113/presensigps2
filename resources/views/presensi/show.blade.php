@foreach ($presensi as $d)
    @php
        $path_in = Storage::url('/uploads/absensi/' . $d->foto_in);
        $path_out = Storage::url('/uploads/absensi/' . $d->foto_out);
    @endphp
    <tr class="{{ $d->jam_in > '08:00' ? 'bg-danger text-white' : '' }}">
        <td>{{ $loop->iteration }}</td>
        <td>{{ $d->nik }}</td>
        <td>{{ $d->nama_lengkap }}</td>
        <td>
            <img src="{{ url($path_in) }}" alt="" class="avatar" style="object-fit: cover">
        </td>
        <td>{{ $d->jam_in }}</td>
        <td class="text-center">
            <div class="avatar">
                @if ($d->foto_out == null)
                    <i class="mdi mdi-camera" style="font-size: 2rem; color:yellow"></i>
                @else
                    <img src="{{ url($path_out) }}" alt="" class="avatar" style="object-fit: cover">
                @endif
            </div>
        </td>
        <td>
            @if ($d->jam_out != '00:00:00')
                {{ $d->jam_out }}
        </td>
    @else
        <span class="badge bg-danger">Belum Absen</span>
@endif
<td>
    <a href="#" class="btn btn-primary showmap" id="{{ $d->id }}"><svg xmlns="http://www.w3.org/2000/svg"
            class="icon icon-tabler icon-tabler-map-2" width="40" height="40" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M18 6l0 .01"></path>
            <path d="M18 13l-3.5 -5a4 4 0 1 1 7 0l-3.5 5"></path>
            <path d="M10.5 4.75l-1.5 -.75l-6 3l0 13l6 -3l6 3l6 -3l0 -2"></path>
            <path d="M9 4l0 13"></path>
            <path d="M15 15l0 5"></path>
        </svg></a>
</td>
</tr>
@endforeach
<script>
    $(function() {
        $(".showmap").click(function(e) {
            var id = $(this).attr('id');
            $.ajax({
                type: "POST",
                url: "/loadmap",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: id
                },
                cache: false,
                success: function(respond) {
                    $("#loadmap").html(respond);
                }
            });
            $("#mdlshowmap").modal("show");
        });
    });
</script>
