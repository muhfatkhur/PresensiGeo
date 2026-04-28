<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today('Asia/Jakarta')->toDateString());
        
        $query = Presensi::with(['user.siswa', 'user.guru'])->whereDate('tanggal', $tanggal);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->whereHas('siswa', function($qSiswa) use ($search) {
                    $qSiswa->where('nama', 'like', "%{$search}%");
                })->orWhereHas('guru', function($qGuru) use ($search) {
                    $qGuru->where('nama', 'like', "%{$search}%");
                });
            });
        }

        if ($request->filled('role') && $request->role != 'semua') {
            $role = $request->role;
            $query->whereHas('user', function($q) use ($role) {
                $q->where('role', $role);
            });
        }

        if ($request->filled('status') && $request->status != 'semua') {
            if ($request->status == 'hadir') {
                $query->whereIn('status', ['hadir', 'tepat waktu', 'terlambat']);
            } else {
                $query->where('status', $request->status);
            }
        }

        $sortBy = $request->input('sort_by', 'waktu');
        $sortOrder = $request->input('sort_order', 'desc');

        if (in_array($sortBy, ['waktu', 'jarak_meter', 'status'])) {
            $query->orderBy($sortBy, $sortOrder);
            $presensis = $query->get();
        } else {
            $presensis = $query->get();
        }

        if ($sortBy === 'nama') {
            $presensis = $presensis->sortBy(function($item) {
                if ($item->user && $item->user->role === 'siswa' && $item->user->siswa) {
                    return $item->user->siswa->nama;
                } elseif ($item->user && $item->user->role === 'guru' && $item->user->guru) {
                    return $item->user->guru->nama;
                }
                return 'Z';
            }, SORT_REGULAR, $sortOrder === 'desc')->values();
        }

        return view('admin.laporan.index', compact('presensis', 'tanggal', 'sortBy', 'sortOrder'));
    }
}