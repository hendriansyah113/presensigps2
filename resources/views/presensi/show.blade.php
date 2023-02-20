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
            <img src="{{ url($path_in) }}" alt="">
        </td>
        <td>{{ $d->jam_in }}</td>
        <td class="text-center">
            @if ($d->foto_out == null)
                <i class="mdi mdi-camera" style="font-size: 2rem; color:yellow"></i>
            @else
                <img src="{{ url($path_out) }}" alt="">
            @endif
        </td>
        <td>
            @if ($d->jam_out != '00:00:00')
                {{ $d->jam_out }}
        </td>
    @else
        <span class="badge bg-danger">Belum Absen</span>
@endif
<td>
    <a href="#" class="btn btn-primary btn-sm showmap" id="{{ $d->id }}"><i
            class="mdi mdi-map-marker"></i></a>
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
