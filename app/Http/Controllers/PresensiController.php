<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class PresensiController extends Controller
{
    public function index()
    {
        return view('presensi.index');
    }
    
    public function show(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $tanggal)
            ->orderBy('jam_in')
            ->get();
        return view('presensi.show', compact('presensi'));
    }
    
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $setjammasuk = "08:00:00";

        $jam1 = strtotime($setjammasuk);
        $jam2 = strtotime($jam);
        if($jam2 >= $jam1){
            $terlambat = 1;
        }else{
            $terlambat = 0;
        }
        
        $latitudekantor = -2.2216646929189547;
        $longtitukantor = 113.93111907278704;
        $lokasi = $request->lokasi;
        $lokasiuser = explode(",", $lokasi);
        $latitudeuser = $lokasiuser[0];
        $longtitudeuser = $lokasiuser[1];
        $jarak = $this->distance($latitudekantor, $longtitukantor, $latitudeuser, $longtitudeuser);
        $radius = round($jarak["meters"]);

        $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nik', $nik)->count();

        if($cek > 0) {
            $ket = "out";
        }else{
            $ket = "in";
        }
        $image = $request->image;
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi . "-". $ket;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        if($radius > 20){
            echo "error|Maaf Anda Berada Diluar Radius, Jarak Anda " . $radius . " Meter Dari Kantor|radius";
        }else{
            if($cek > 0){
                $data_pulang = [
                    'jam_out' => $jam,
                    'foto_out' => $fileName,
                    'lokasi_out' => $lokasi
                ];
                $update = DB::table('presensi')->where('tgl_presensi',$tgl_presensi)->where('nik',$nik)->update($data_pulang);
                if($update){
                    echo "success|Terimakasih, Data Presensi Pulang Berhasil Disimpan|out";
                    Storage::put($file, $image_base64);
                }else{
                    echo "error|Maaf Gagal Absen, Coba Ulangi Lagi|out";
                }
            }else{
                $data = [
                    'nik' => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in' => $jam,
                    'jam_out' => "00:00:00",
                    'foto_in' => $fileName,
                    'lokasi_in' => $lokasi,
                    'terlambat' => $terlambat,
                ];
                $simpan = DB::table('presensi')->insert($data);
                if($simpan){
                    echo "success|Terimakasih, Data Presensi Masuk Berhasil Disimpan|in";
                    Storage::put($file, $image_base64);
                }else{
                    echo "error|Maaf Gagal Absen, Coba Ulangi Lagi|out";
                }
            }
        }
    }

     //Menghitung Jarak
     function distance($lat1, $lon1, $lat2, $lon2)
     {
         $theta = $lon1 - $lon2;
         $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
         $miles = acos($miles);
         $miles = rad2deg($miles);
         $miles = $miles * 60 * 1.1515;
         $feet = $miles * 5280;
         $yards = $feet / 3;
         $kilometers = $miles * 1.609344;
         $meters = $kilometers * 1000;
         return compact('meters');
     }

     public function histori(){
        $bulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.histori', compact('bulan'));
     }

     public function gethistori(Request $request)
     {
        $nik = Auth::guard('karyawan')->user()->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $histori = DB::table('presensi')->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')->where('nik', $nik)->get();

        return view('presensi.gethistori', compact('histori'));
     }

     public function create_izin()
     {
        return view('presensi.create_izin');
     }

     public function store_izin(Request $request)
     {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tanggal = $request->tanggal;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tanggal' => $tanggal,
            'status' => $status,
            'keterangan' => $keterangan,
            'status_approve' => 0,
        ];

        $simpan = DB::table('izin')->insert($data);
        if($simpan){
            return redirect('presensi/izin')->with(['success' => 'Data Berhasil Diajukan']);
        }else{
            return redirect('presensi/izin')->with(['error' => 'Data Gagal Diajukan']);
        }
     }

     public function show_izin()
     {
        $nik = Auth::guard('karyawan')->user()->nik;
        $izin = DB::table('izin')->join('karyawan', 'izin.nik', '=', 'karyawan.nik')->where('izin.nik', $nik)->get();
        return view('presensi.show_izin', compact('izin'));
     }

     public function laporan()
     {
        $bulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.laporan', compact('bulan'));
     }

     public function cetak(Request $request)
     {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bln = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $presensi = DB::table('presensi')->selectRaw('presensi.nik, nama_lengkap, 
            MAX(IF(DAY(tgl_presensi)= 1, CONCAT(jam_in,"-",jam_out),0)) as tgl_1,
            MAX(IF(DAY(tgl_presensi)= 2, CONCAT(jam_in,"-",jam_out),0)) as tgl_2,
            MAX(IF(DAY(tgl_presensi)= 3, CONCAT(jam_in,"-",jam_out),0)) as tgl_3,
            MAX(IF(DAY(tgl_presensi)= 4, CONCAT(jam_in,"-",jam_out),0)) as tgl_4,
            MAX(IF(DAY(tgl_presensi)= 5, CONCAT(jam_in,"-",jam_out),0)) as tgl_5,
            MAX(IF(DAY(tgl_presensi)= 6, CONCAT(jam_in,"-",jam_out),0)) as tgl_6,
            MAX(IF(DAY(tgl_presensi)= 7, CONCAT(jam_in,"-",jam_out),0)) as tgl_7,
            MAX(IF(DAY(tgl_presensi)= 8, CONCAT(jam_in,"-",jam_out),0)) as tgl_8,
            MAX(IF(DAY(tgl_presensi)= 9, CONCAT(jam_in,"-",jam_out),0)) as tgl_9,
            MAX(IF(DAY(tgl_presensi)= 10, CONCAT(jam_in,"-",jam_out),0)) as tgl_10,
            MAX(IF(DAY(tgl_presensi)= 11, CONCAT(jam_in,"-",jam_out),0)) as tgl_11,
            MAX(IF(DAY(tgl_presensi)= 12, CONCAT(jam_in,"-",jam_out),0)) as tgl_12,
            MAX(IF(DAY(tgl_presensi)= 13, CONCAT(jam_in,"-",jam_out),0)) as tgl_13,
            MAX(IF(DAY(tgl_presensi)= 14, CONCAT(jam_in,"-",jam_out),0)) as tgl_14,
            MAX(IF(DAY(tgl_presensi)= 15, CONCAT(jam_in,"-",jam_out),0)) as tgl_15,
            MAX(IF(DAY(tgl_presensi)= 16, CONCAT(jam_in,"-",jam_out),0)) as tgl_16,
            MAX(IF(DAY(tgl_presensi)= 17, CONCAT(jam_in,"-",jam_out),0)) as tgl_17,
            MAX(IF(DAY(tgl_presensi)= 18, CONCAT(jam_in,"-",jam_out),0)) as tgl_18,
            MAX(IF(DAY(tgl_presensi)= 19, CONCAT(jam_in,"-",jam_out),0)) as tgl_19,
            MAX(IF(DAY(tgl_presensi)= 20, CONCAT(jam_in,"-",jam_out),0)) as tgl_20,
            MAX(IF(DAY(tgl_presensi)= 21, CONCAT(jam_in,"-",jam_out),0)) as tgl_21,
            MAX(IF(DAY(tgl_presensi)= 22, CONCAT(jam_in,"-",jam_out),0)) as tgl_22,
            MAX(IF(DAY(tgl_presensi)= 23, CONCAT(jam_in,"-",jam_out),0)) as tgl_23,
            MAX(IF(DAY(tgl_presensi)= 24, CONCAT(jam_in,"-",jam_out),0)) as tgl_24,
            MAX(IF(DAY(tgl_presensi)= 25, CONCAT(jam_in,"-",jam_out),0)) as tgl_25,
            MAX(IF(DAY(tgl_presensi)= 26, CONCAT(jam_in,"-",jam_out),0)) as tgl_26,
            MAX(IF(DAY(tgl_presensi)= 27, CONCAT(jam_in,"-",jam_out),0)) as tgl_27,
            MAX(IF(DAY(tgl_presensi)= 28, CONCAT(jam_in,"-",jam_out),0)) as tgl_28,
            MAX(IF(DAY(tgl_presensi)= 29, CONCAT(jam_in,"-",jam_out),0)) as tgl_29,
            MAX(IF(DAY(tgl_presensi)= 30, CONCAT(jam_in,"-",jam_out),0)) as tgl_30,
            MAX(IF(DAY(tgl_presensi)= 31, CONCAT(jam_in,"-",jam_out),0)) as tgl_31')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupByRaw('presensi.nik,nama_lengkap')
            ->get();

        return view('presensi.cetak', compact('presensi', 'bulan', 'tahun', 'bln'));
     }

     public function loadmap(Request $request)
     {
        $id = $request->id;
        $presensi = DB::table('presensi')
        ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
        ->where('id', $id)->first();
        return view('presensi.showmap', compact('presensi'));
     }
}