<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class QrController extends Controller
{
    /**
     * Generate token QR baru (dipanggil tiap 5 detik dari frontend).
     * Satu sesi hanya boleh ada satu jenis_sesi aktif dalam satu waktu.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'jenis_sesi' => 'required|in:Hari H,Gladi',
        ]);

        // Hapus token lama yang sudah expired untuk jenis sesi ini
        DB::table('sesi_qr')
            ->where('jenis_sesi', $request->jenis_sesi)
            ->where('expired_at', '<', now())
            ->delete();

        $token = Str::random(32);
        $expiredAt = now()->addSeconds(6); // 6 detik sedikit lebih dari interval 5 detik

        $id = DB::table('sesi_qr')->insertGetId([
            'jenis_sesi' => $request->jenis_sesi,
            'token'      => $token,
            'expired_at' => $expiredAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'id'          => $id,
            'token'       => $token,
            'jenis_sesi'  => $request->jenis_sesi,
            'expired_at'  => $expiredAt->toIso8601String(),
        ]);
    }

    /**
     * Ambil token aktif terkini untuk jenis sesi tertentu.
     */
    public function active(Request $request)
    {
        $request->validate([
            'jenis_sesi' => 'required|in:Hari H,Gladi',
        ]);

        $sesi = DB::table('sesi_qr')
            ->where('jenis_sesi', $request->jenis_sesi)
            ->where('expired_at', '>', now())
            ->orderByDesc('created_at')
            ->first();

        if (! $sesi) {
            return response()->json(['message' => 'Tidak ada token aktif.'], 404);
        }

        return response()->json($sesi);
    }
}
