<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;

class AuthController extends Controller
{
    public function loginPeserta(Request $request)
    {
        $request->validate([
            'nic'   => 'required|string',
            'divisi'=> 'required|string',
        ]);

        $peserta = DB::table('peserta')
            ->where('nic',    $request->nic)
            ->where('divisi', $request->divisi)
            ->first();

        if (! $peserta) {
            return response()->json(['message' => 'NIC atau Divisi tidak ditemukan.'], 401);
        }

        // Buat fake user model untuk Sanctum
        // Karena peserta tidak punya model Authenticatable,
        // kita pakai session sederhana via custom token
        $token = base64_encode("peserta:{$peserta->nic}:{$peserta->divisi}:" . time());

        return response()->json([
            'role'   => 'peserta',
            'nic'    => $peserta->nic,
            'divisi' => $peserta->divisi,
            'token'  => $token,
        ]);
    }

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $admin = Admin::where('username', $request->username)->first();

        if (! $admin || ! Hash::check($request->password, $admin->password)) {
            return response()->json(['message' => 'Username atau password salah.'], 401);
        }

        $token = $admin->createToken('admin-token')->plainTextToken;

        return response()->json([
            'role'  => 'admin',
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
        }
        return response()->json(['message' => 'Logged out.']);
    }
}
