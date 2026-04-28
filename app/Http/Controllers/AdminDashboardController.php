<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presensi;
use App\Models\Geofencing;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::now('Asia/Jakarta')->toDateString();
        $geofencing = Geofencing::first();
        $is_open = $geofencing ? $geofencing->is_open : 0;

        $hadirSiswa = Presensi::whereHas('user', function($q) {
            $q->where('role', 'siswa');
        })->where('tanggal', $hariIni)->whereIn('status', ['hadir', 'tepat waktu'])->count();

        $hadirGuru = Presensi::whereHas('user', function($q) {
            $q->where('role', 'guru');
        })->where('tanggal', $hariIni)->whereIn('status', ['hadir', 'tepat waktu'])->count();

        $terlambatSiswa = Presensi::whereHas('user', function($q) {
            $q->where('role', 'siswa');
        })->where('tanggal', $hariIni)->where('status', 'terlambat')->count();

        $terlambatGuru = Presensi::whereHas('user', function($q) {
            $q->where('role', 'guru');
        })->where('tanggal', $hariIni)->where('status', 'terlambat')->count();

        $tidakHadirSiswa = Presensi::whereHas('user', function($q) {
            $q->where('role', 'siswa');
        })->where('tanggal', $hariIni)->whereIn('status', ['alpha', 'izin', 'sakit'])->count();

        $tidakHadirGuru = Presensi::whereHas('user', function($q) {
            $q->where('role', 'guru');
        })->where('tanggal', $hariIni)->whereIn('status', ['alpha', 'izin', 'sakit'])->count();

        $recentActivities = Presensi::with(['user.siswa', 'user.guru'])
            ->where('tanggal', $hariIni)
            ->latest('waktu')
            ->take(5)
            ->get()
            ->map(function ($item) use ($geofencing) {
                $nama = $item->user->name ?? 'Tidak Diketahui';
                $keteranganRole = '-';
                
                if ($item->user) {
                    if ($item->user->role === 'siswa' && $item->user->siswa) {
                        $nama = $item->user->siswa->nama;
                        $keteranganRole = 'Siswa - ' . $item->user->siswa->kelas;
                    } elseif ($item->user->role === 'guru' && $item->user->guru) {
                        $nama = $item->user->guru->nama;
                        $keteranganRole = 'Guru - ' . $item->user->guru->mata_pelajaran;
                    }
                }

                $radius = $geofencing ? $geofencing->radius_meter : 50;

                if (in_array(strtolower($item->status), ['hadir', 'tepat waktu'])) {
                    $jarakTeks = $item->jarak_meter . ' Meter';
                    $statusRadius = $item->jarak_meter <= $radius ? 'sah' : 'luar_radius';
                    $statusKehadiran = $item->keterangan ?? 'Tepat Waktu';
                } else {
                    $jarakTeks = '-';
                    $statusRadius = 'sah';
                    $statusKehadiran = strtoupper($item->status);
                }

                return [
                    'name' => $nama,
                    'keterangan_role' => $keteranganRole,
                    'time' => $item->waktu ? Carbon::parse($item->waktu)->format('H:i') . ' WIB' : '-',
                    'distance' => $jarakTeks,
                    'status_radius' => $statusRadius,
                    'status_kehadiran' => $statusKehadiran,
                ];
            });

        return view('admin.dashboard.index', compact(
            'geofencing',
            'hadirSiswa', 'hadirGuru',
            'terlambatSiswa', 'terlambatGuru',
            'tidakHadirSiswa', 'tidakHadirGuru',
            'recentActivities'
        ));
    }
}