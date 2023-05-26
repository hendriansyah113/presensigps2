<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*', 'nama_dept');
        $query->join('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }
        
        if(!empty($request->kode_dept)){
            $query->where('karyawan.kode_dept', 'like', '%' . $request->kode_dept . '%');
        }

        $karyawan = $query->paginate(5);

        $departemen = DB::table('departemen')->get();
        return view('karyawan.index', compact('karyawan', 'departemen'));
    }
    
    public function editmobile()
     {
        $nip = Auth::guard('karyawan')->user()->nip;
        $karyawan = DB::table('karyawan')->where('nip', $nip)->first();
        return view('karyawan.editmobile', compact('karyawan'));
     }

    public function update(Request $request)
    {
        $nip = Auth::guard('karyawan')->user()->nip;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = Karyawan::where('nip', $nip)->first();

        // Validasi email unik
        $email = $request->email;
        $isEmailUnique = Karyawan::where('email', $email)
            ->where('nip', '!=', $nip)
            ->doesntExist();
        if (!$isEmailUnique) {
            return redirect()->back()->with('error', 'Email sudah digunakan');
        }

        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $karyawan->foto;
        }

        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'email' => $email,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'email' => $email,
                'password' => $password,
                'foto' => $foto
            ];
        }

        $update = DB::table('karyawan')->where('nip', $nip)->update($data);

        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/karyawan/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Diupdate']);
        }
    }


     public function store(Request $request)
     {
        $nip = $request->nip;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $email = $request->email;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('123456');
        if($request->hasFile('foto')){
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = null;
        }

        // Validasi email unik
        $isEmailUnique = Karyawan::where('email', $email)->doesntExist();
        if (!$isEmailUnique) {
            return Redirect::back()->with(['warning' => 'Email sudah digunakan']);
        }

        try{
            $data = [
                'nip' => $nip,
                'nama_lengkap' => $nama_lengkap,
                'jabatan' => $jabatan,
                'no_hp' => $no_hp,
                'email' => $email,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $simpan = DB::table('karyawan')->insert($data);
            if($simpan){
                if($request->hasFile('foto')){
                    $folderPath = "public/uploads/karyawan/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
            }
        }catch(\Exception $e){
            if($e->getCode() == 23000){
                $message = ", Data dengan nip " . $nip . " Sudah Ada";
            }
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan' . $message]);
        }
     }

     public function edit(Request $request)
     {
        $nip = $request->nip;
        $departemen = DB::table('departemen')->get();
        $karyawan = DB::table('karyawan')->where('nip', $nip)->first();
        return view('karyawan.edit', compact('departemen', 'karyawan'));
     }

     public function updatepanel($nip, Request $request)
    {
        $nip = $request->nip;
        $nama_lengkap = $request->nama_lengkap;
        $jabatan = $request->jabatan;
        $no_hp = $request->no_hp;
        $email = $request->email;
        $kode_dept = $request->kode_dept;
        $old_foto = $request->old_foto;
        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $old_foto;
        }

        // Mengambil data karyawan yang sedang diedit
        $karyawan = Karyawan::where('nip', $nip)->first();

        // Validasi email unik
        $isEmailUnique = Karyawan::where('email', $email)->where('nip', '!=', $nip)->doesntExist();
        if ($isEmailUnique || $email === $karyawan->email) {
            try {
                $data = [
                    'nama_lengkap' => $nama_lengkap,
                    'jabatan' => $jabatan,
                    'no_hp' => $no_hp,
                    'email' => $email,
                    'kode_dept' => $kode_dept,
                    'foto' => $foto,
                ];
                $update = DB::table('karyawan')->where('nip', $nip)->update($data);
                if ($update) {
                    if ($request->hasFile('foto')) {
                        $folderPath = "public/uploads/karyawan/";
                        $folderPathOld = "public/uploads/karyawan/" . $old_foto;
                        Storage::delete($folderPathOld);
                        $request->file('foto')->storeAs($folderPath, $foto);
                    }
                    return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
                }
            } catch (\Exception $e) {
                return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
            }
        } else {
            return Redirect::back()->with(['warning' => 'Email sudah digunakan']);
        }
    }

     public function delete($nip)
     {
        $delete = DB::table('karyawan')->where('nip', $nip)->delete();
        if($delete){
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        }else{
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
     }
}