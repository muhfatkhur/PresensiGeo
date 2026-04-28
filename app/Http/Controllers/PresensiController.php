<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Presensi;
use App\Models\Geofencing;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function index()
    {
        $geofencing = Geofencing::first();
        return view('presensi.index', compact('geofencing'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
            'alasan' => 'required_if:status,izin,sakit'
        ]);

        $geofencing = Geofencing::first();

        if (!$geofencing) {
            return back()->withErrors('Pengaturan lokasi belum diset oleh admin.');
        }

        if (isset($geofencing->is_open) && !$geofencing->is_open) {
            return back()->withErrors('Presensi telah ditutup');
        }

        $user = Auth::user();
        
        date_default_timezone_set('Asia/Jakarta');
        $hariIni = date('Y-m-d');
        $waktuSekarang = date('H:i:s');

        $cekAbsen = Presensi::where('user_id', $user->id)
            ->whereDate('tanggal', $hariIni)
            ->first();

        if ($cekAbsen) {
            return back()->withErrors('sudah absen hari ini, tidak bisa absen lagi');
        }

        $jarak = null;
        $keterangan = null;
        $latitude = null;
        $longitude = null;

        if ($request->status === 'hadir') {
            $request->validate([
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);

            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $jarak = $this->hitungJarak($latitude, $longitude, $geofencing->latitude, $geofencing->longitude);

            if ($jarak > $geofencing->radius_meter) {
                return back()->withErrors('Posisi anda di luar radius sekolah! Jarak anda: ' . round($jarak) . ' meter.');
            }

            $jamTerlambat = $geofencing->batas_tepat_waktu;
            $keterangan = ($waktuSekarang > $jamTerlambat) ? 'Terlambat' : 'Tepat Waktu';
        }

        $presensi = Presensi::create([
            'user_id' => $user->id,
            'tanggal' => $hariIni,
            'waktu' => $waktuSekarang,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'jarak_meter' => $jarak,
            'status' => $request->status,
            'keterangan' => $keterangan,
            'alasan' => $request->status !== 'hadir' ? $request->alasan : null,
        ]);

        if ($user->role === 'siswa' && $user->siswa_id) {
            $siswa = Siswa::find($user->siswa_id);
            
            if ($siswa && $siswa->no_wa_wali) {
                $pesan = "*NOTIFIKASI PRESENSI SMK*\n\n";
                $pesan .= "Halo Bapak/Ibu dari *" . $siswa->nama . "*,\n";
                $pesan .= "Anak anda telah melakukan presensi dengan detail:\n\n";
                $pesan .= "📅 Tanggal: *" . $hariIni . "*\n";
                $pesan .= "⏰ Jam: *" . $waktuSekarang . "*\n";
                $pesan .= "📍 Status: *" . strtoupper($request->status) . "*\n";
                
                if ($request->status !== 'hadir') {
                    $pesan .= "📝 Alasan: *" . $request->alasan . "*\n\n";
                } else {
                    $pesan .= "⏳ Keterangan: *" . strtoupper($keterangan) . "*\n\n";
                }
                
                $pesan .= "Terima kasih.";

                $response = Http::withHeaders([
                    'Authorization' => env('FONNTE_TOKEN')
                ])->post('https://api.fonnte.com/send', [
                    'target' => $siswa->no_wa_wali,
                    'message' => $pesan,
                    'countryCode' => '62',
                ]);

                DB::table('whatsapp_log')->insert([
                    'presensi_id' => $presensi->id,
                    'no_wa' => $siswa->no_wa_wali,
                    'pesan' => $pesan,
                    'status' => $response->successful() ? 'sent' : 'failed',
                    'created_at' => now()
                ]);
            }
        }

        $pesanSukses = $request->status === 'hadir' 
            ? 'Presensi berhasil dicatat. Keterangan: ' . $keterangan 
            : 'Pengajuan ' . $request->status . ' berhasil dicatat.';

        return back()->with('success', $pesanSukses);
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        
        return $earthRadius * $c;
    }
}