<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Siswa;
use Carbon\Carbon;

class GuruDashboardController extends Controller
{
    public function index(Request $request)
    {
        $hariIni = Carbon::today('Asia/Jakarta')->toDateString();
        $kelasFilter = $request->input('kelas');

        $queryPresensi = Presensi::with('user.siswa')
            ->where('tanggal', $hariIni)
            ->whereHas('user', function($q) {
                $q->where('role', 'siswa');
            });

        if ($kelasFilter && $kelasFilter !== 'semua') {
            $queryPresensi->whereHas('user.siswa', function($q) use ($kelasFilter) {
                $q->where('kelas', $kelasFilter);
            });
        }

        $presensis = $queryPresensi->orderBy('waktu', 'desc')->get();

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas', 'asc')->pluck('kelas');

        return view('guru.dashboard', compact('presensis', 'daftarKelas', 'kelasFilter'));
    }
}