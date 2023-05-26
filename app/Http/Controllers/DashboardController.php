<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nip = Auth::guard('karyawan')->user()->nip;
        
        $presensihariini = DB::table('presensi')
            ->where('nip', $nip)
            ->where('tgl_presensi', $hariini)
            ->first();

        $historibulanini = DB::table('presensi')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();
        

        $rekappresensi = DB::table('presensi')
            ->selectRaw('SUM(IF(terlambat=1,1,0)) as jmlterlambat,SUM(IF(terlambat=0,1,0)) as jmltepatwaktu,COUNT(id) as jmlhadir')
            ->selectRaw('COUNT(nip) as jmlhadir, SUM(IF(jam_in > "08:00",1,0)) as jmlterlambat')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.nip', '=', 'karyawan.nip')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();
        
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        $izin = DB::table('izin')
        ->selectRaw('SUM(IF(status="i" AND status_approve=1,1,0)) as jmlizin, SUM(IF(status="s" AND status_approve=1,1,0)) as jmlsakit')
        ->where('nip', $nip)
        ->whereRaw('MONTH(tanggal)="' . date('m') . '"')
        ->whereRaw('YEAR(tanggal)="' . date('Y') . '"')
        ->first();    

        return view('dashboard.dashboard', compact('presensihariini', 'historibulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard', 'izin'));   
    }

    public function dashboardadmin()
    {
        $jamTerlambat = '08:00:00';
        $hariini = date("Y-m-d");
        $jmlkaryawan = DB::table('karyawan')->count();
        $jmlhadir = DB::table('presensi')->where('tgl_presensi', $hariini)->count();
        $jmlizin = DB::table('izin')->where('tanggal', $hariini)->count();
        $jmlterlambat = DB::table('presensi')
            ->where('terlambat', 1)
            ->where('tgl_presensi', $hariini)
            ->whereTime('jam_in', '>', $jamTerlambat)
            ->count();
        return view('dashboard_admin', compact('jmlkaryawan', 'jmlhadir', 'jmlizin', 'jmlterlambat'));
    }
}