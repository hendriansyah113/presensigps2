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
                </td>
                <?php
                }
                ?>
                <td>{{ $total }}</td>
                <td>{{ $terlambat }}</td>
            </tr>
        @endforeach
    </table>
</body>

</html>
