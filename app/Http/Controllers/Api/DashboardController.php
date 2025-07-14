<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Book;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // ✅ Update status peminjaman jika terlambat
        $today = Carbon::today();
        Peminjaman::where('status', 'dipinjam')
            ->whereDate('tanggal_kembali', '<', $today)
            ->update(['status' => 'terlambat']);

        // ✅ Hitung total data
        $totalAnggota = User::where('role', 'anggota')->count();
        $totalBuku = Book::count();
        $totalPeminjaman = Peminjaman::count();
        $totalTerlambat = Peminjaman::where('status', 'terlambat')->count();

        // ✅ Ambil data anggota
        $anggotaList = User::where('role', 'anggota')
            ->select('id', 'name as nama', 'phone as nomorTelepon', 'gender')
            ->get();

        return response()->json([
            'totalAnggota' => $totalAnggota,
            'totalBuku' => $totalBuku,
            'totalPeminjaman' => $totalPeminjaman,
            'totalTerlambat' => $totalTerlambat,
            'anggotaList' => $anggotaList,
        ]);
    }
}
