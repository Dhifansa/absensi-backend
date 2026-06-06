<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    /**
     * Peserta scan QR — validasi token lalu catat presensi.
     */
    public function scan(Request $request)
    {
        $request->validate([
            'token'  => 'required|string',
            'nic'    => 'required|string',
            'divisi' => 'required|string',
        ]);

        // 1. Cek apakah token valid dan belum expired
        $sesi = DB::table('sesi_qr')
            ->where('token', $request->token)
            ->where('expired_at', '>', now())
            ->first();

        if (! $sesi) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau sudah kedaluwarsa.',
            ], 422);
        }

        // 2. Cek apakah peserta terdaftar
        $peserta = DB::table('peserta')
            ->where('nic',    $request->nic)
            ->where('divisi', $request->divisi)
            ->first();

        if (! $peserta) {
            return response()->json([
                'success' => false,
                'message' => 'Peserta tidak ditemukan dalam database.',
            ], 404);
        }

        // 3. Cek apakah sudah presensi di sesi ini
        $sudah = DB::table('presensi')
            ->where('nic',        $request->nic)
            ->where('divisi',     $request->divisi)
            ->where('jenis_sesi', $sesi->jenis_sesi)
            ->exists();

        if ($sudah) {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah presensi untuk sesi {$sesi->jenis_sesi}.",
            ], 409);
        }

        // 4. Simpan presensi
        $waktu = now();
        DB::table('presensi')->insert([
            'nic'        => $request->nic,
            'divisi'     => $request->divisi,
            'jenis_sesi' => $sesi->jenis_sesi,
            'waktu'      => $waktu,
            'created_at' => $waktu,
            'updated_at' => $waktu,
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Presensi berhasil dicatat!',
            'nic'        => $request->nic,
            'divisi'     => $request->divisi,
            'jenis_sesi' => $sesi->jenis_sesi,
            'waktu'      => $waktu->format('d M Y, H:i:s'),
        ]);
    }

    /**
     * Ambil semua data presensi (admin).
     */
    public function index(Request $request)
    {
        $query = DB::table('presensi')->orderByDesc('waktu');

        if ($request->filled('jenis_sesi')) {
            $query->where('jenis_sesi', $request->jenis_sesi);
        }
        if ($request->filled('divisi')) {
            $query->where('divisi', $request->divisi);
        }

        $data = $query->get();

        return response()->json($data);
    }

    /**
     * Export presensi ke CSV.
     */
    public function export(Request $request)
    {
        $query = DB::table('presensi')->orderByDesc('waktu');

        if ($request->filled('jenis_sesi')) {
            $query->where('jenis_sesi', $request->jenis_sesi);
        }

        $rows = $query->get();

        $filename = 'presensi_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            // BOM untuk Excel UTF-8
            fputs($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['NIC', 'Divisi', 'Jenis Sesi', 'Waktu Presensi']);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row->nic,
                    $row->divisi,
                    $row->jenis_sesi,
                    $row->waktu,
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
