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
            ->select('presensi.*', 'nama_lengkap', 'nama_dept')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();
        return view('presensi.show', compact('presensi'));
    }
    
    public function create()
    {
        $lokasi = DB::table('lokasi')->where('id', 1)->first();
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nik', $nik)->count();
        return view('presensi.create', compact('cek', 'lokasi'));
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
  
        $lokasikantor = DB::table('lokasi')->where('id', 1)->first();
        $lokkantor = explode(",", $lokasikantor->lokasi);
        $latitudekantor = $lokkantor[0];
        $longtitukantor = $lokkantor[1];
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

        if($radius > 100){
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
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'karyawan'));
     }

     public function cetaklaporan(Request $request)
     {
        $nik = $request->nik;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $karyawan = DB::table('karyawan')->where('nik', $nik)
            ->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->get();

        return view('presensi.cetaklaporan', compact('karyawan', 'bulan', 'tahun', 'namabulan', 'presensi'));
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