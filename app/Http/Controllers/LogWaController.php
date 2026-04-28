<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WhatsappLog;
use Carbon\Carbon;

class LogWaController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today('Asia/Jakarta')->toDateString());

        $logs = WhatsappLog::with(['presensi.user.siswa', 'presensi.user.guru'])
            ->whereDate('created_at', $tanggal)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.log-wa.partials.table', compact('logs'))->render()
            ]);
        }

        return view('admin.log-wa.index', compact('logs', 'tanggal'));
    }
}