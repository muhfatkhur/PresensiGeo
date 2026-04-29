<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Geofencing;
use App\Models\Presensi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GeofencingController extends Controller
{
    public function index()
    {
        $geofencing = Geofencing::first(); 
        return view('admin.geofencing.index', compact('geofencing'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_meter' => 'required|integer|min:10|max:1000',
            'batas_tepat_waktu' => 'required'
        ]);

        Geofencing::updateOrCreate(
            ['id' => 1],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius_meter' => $request->radius_meter,
                'batas_tepat_waktu' => $request->batas_tepat_waktu
            ]
        );

        return redirect()->route('admin.geofencing.index')->with('success', 'Pengaturan Geofencing berhasil diperbarui.');
    }

    public function toggle()
    {
        $geofencing = Geofencing::first();
        
        if ($geofencing) {
            $statusBaru = !$geofencing->is_open;
            $geofencing->update(['is_open' => $statusBaru]);

            $hariIni = Carbon::today('Asia/Jakarta')->toDateString();

            if (!$statusBaru) {
                $waktuEksekusi = Carbon::now('Asia/Jakarta')->format('H:i:s');

                $userBelumAbsen = User::with('siswa')
                    ->whereIn('role', ['siswa', 'guru'])
                    ->whereDoesntHave('presensis', function($query) use ($hariIni) {
                        $query->whereDate('tanggal', $hariIni);
                    })
                    ->get();

                foreach ($userBelumAbsen as $user) {
                    $presensi = Presensi::create([
                        'user_id' => $user->id,
                        'tanggal' => $hariIni,
                        'waktu' => $waktuEksekusi,
                        'latitude' => null,
                        'longitude' => null,
                        'jarak_meter' => null,
                        'status' => 'alpha',
                        'keterangan' => 'Alpha ',
                        'alasan' => 'Tidak melakukan presensi hingga gerbang ditutup'
                    ]);

                    if ($user->role === 'siswa' && $user->siswa && $user->siswa->no_wa_wali) {
                        $siswa = $user->siswa;
                        $pesan = "*NOTIFIKASI PRESENSI SMK*\n\n";
                        $pesan .= "Halo Orang Tua dari *" . $siswa->nama . "*,\n";
                        $pesan .= "Kami memberitahukan bahwa anak Anda hari ini tercatat dengan detail:\n\n";
                        $pesan .= "📅 Tanggal: *" . $hariIni . "*\n";
                        $pesan .= "⏰ Jam: *" . $waktuEksekusi . "*\n";
                        $pesan .= "📍 Status: *ALPHA*\n";
                        $pesan .= "📝 Keterangan: *Alpha *\n\n";
                        $pesan .= "Anak Anda tidak melakukan presensi kehadiran hingga batas waktu tutup gerbang yang telah ditentukan. Terima kasih.";

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

                $pesanInformasi = "Gerbang ditutup. Sisa user otomatis tercatat Alpha dan notifikasi WhatsApp telah dikirim ke wali murid.";
            } else {
                $pesanInformasi = "Gerbang presensi berhasil dibuka.";
            }

            return redirect()->back()->with('success', $pesanInformasi);
        }

        return redirect()->back()->withErrors('Harap atur lokasi geofencing terlebih dahulu sebelum mengubah status gerbang.');
    }
}