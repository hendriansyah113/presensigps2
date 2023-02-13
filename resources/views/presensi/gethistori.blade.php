 @if ($histori->isEmpty())
     <div class="alert alert-outline-warning">
         Data Belum Tersedia
     </div>
 @else
     <ul class="listview image-listview">
         @foreach ($histori as $d)
             @php
                 $path = Storage::url('uploads/absensi/' . $d->foto_in);
             @endphp
             <li>
                 <div class="item">
                     <img src="{{ url($path) }}" alt="image" class="image">
                     <div class="in">
                         <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                         <span class="badge bg-success">{{ date('H:i', strtotime($d->jam_in)) }}</span>
                         <span
                             class="badge bg-danger">{{ $d->jam_out != null ? date('H:i', strtotime($d->jam_out)) : 'Belum Absen' }}</span>
                     </div>
                 </div>
             </li>
         @endforeach
     </ul>
 @endif
