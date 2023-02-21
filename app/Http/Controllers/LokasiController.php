<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class LokasiController extends Controller
{
    public function create(){
        $ceklokasi = DB::table('lokasi')->where('id', 1)->first();
        return view('lokasi.create', compact('ceklokasi'));
    }

    public function store(Request $request)
    {
        $lokasi = $request->lokasi;
        $ceklokasi = DB::table('lokasi')->where('id', 1)->count();
        if($ceklokasi > 0){
            $simpan = DB::table('lokasi')->where('id', 1)->update([
                'lokasi' => $lokasi
            ]);
        }else{
            $simpan = DB::table('lokasi')->insert([
                'lokasi' => $lokasi
            ]);
        }

        if($simpan){
            return Redirect::back()->with(['success' => 'Data Lokasi Berhasil Disimpan']);
        }else{
            return Redirect::back()->with(['error' => 'Data Lokasi Gagal Disimpan']);
        }
    }
}