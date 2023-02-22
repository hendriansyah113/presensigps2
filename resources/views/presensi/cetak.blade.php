<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Laporan</title>
    <style>
        #customers {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 15px;
            border-collapse: collapse;
            width: 100%;
        }

        #customers td,
        #customers th {
            border: 1px solid #ddd;
            padding: 2px;
        }

        #customers tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        #customers tr:hover {
            background-color: #ddd;
        }

        #customers th {
            text-align: center;
            background-color: #0000FF;
            color: white;
        }
    </style>
</head>

<body>
    <?php
    function selisih($jam_masuk, $jam_keluar)
    {
        [$h, $m, $s] = explode(':', $jam_masuk);
        $dtAwal = mktime($h, $m, $s, '1', '1', '1');
        [$h, $m, $s] = explode(':', $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;
        $jam = explode('.', $totalmenit / 60);
        $sisamenit = $totalmenit / 60 - $jam[0];
        $sisamenit2 = $sisamenit * 60;
        $jml_jam = $jam[0];
        return $jml_jam . ':' . round($sisamenit2);
    }
    ?>
    <h4 style="font-family:'Poppins'; margin-top:20px">
        LAPORAN PRESENSI KARYAWAN
        <br>
        PERIODE {{ strtoupper($bln[$bulan]) }} {{ $tahun }}
    </h4>
    <table id="customers">
        <tr>
            <th rowspan="2">No.</th>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Lengkap</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">Jam Kerja</th>
            <th rowspan="2">Total Hadir</th>
            <th rowspan="2">Terlambat</th>
        </tr>
        <tr>
            @for ($i = 1; $i <= 31; $i++)
                <th>{{ $i }}</th>
            @endfor
        </tr>
        @foreach ($presensi as $d)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $d->nik }}</td>
                <td>{{ $d->nama_lengkap }}</td>
                <?php
                $total = 0;
                $terlambat = 0;
                $totaljamkerja = 0;
                $totalmenitkerja = 0;
                for($i=1; $i<=31; $i++){
                    $tgl = "tgl_".$i;
                    $hadir = $d->$tgl;
                    if (!empty($hadir)) {
                        $jam = explode("-", $hadir);
                        $jam_masuk = $jam[0];
                        $jam_pulang = $jam[1];
                        if($jam_masuk > "08:00:00"){
                            $color = "red";
                            $terlambat += 1;
                        }else{
                            $color = "green";
                        }
                         $total += 1;
                    }else{
                        $jam_masuk = "";
                        $jam_pulang = "";
                        $color = "";
                    }
                ?>
                <td>
                    <span style="color: {{ $color }}">{{ $jam_masuk }}</span>
                    <span style="color:#0000FF">{{ $jam_pulang }}</span>
                    <span style="font-weight:bold">
                        @if (!empty($hadir))
                            {{ $jam_pulang != '00:00:00' ? ($jam_kerja = selisih($jam_masuk, $jam_pulang)) : ($jam_kerja = '0:0') }}
                        @else
                            @php
                                $jam_kerja = '0:0';
                            @endphp
                        @endif
                        @php
                            $j = explode(':', $jam_kerja);
                            $totaljamkerja += $j[0];
                            $totalmenitkerja += round($j[1] / 60, 1);
                        @endphp
                    </span>
                </td>
                <?php
                }
                ?>
                <td>{{ $totaljamkerja + $totalmenitkerja }} Jam</td>
                <td>{{ $total }}</td>
                <td>{{ $terlambat }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
