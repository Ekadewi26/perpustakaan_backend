<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LoanController extends Controller
{
    // ✅ Untuk admin: tampilkan semua peminjaman
  {
        $loans = Loan::with('book', 'user')->get();

        $formatted = $loans->map(function ($loan) {
            return [
                'id' => $loan->id,
                'nama_peminjam' => $loan->user->name ?? 'Tidak diketahui',
                'jurusan' => $loan->user->jurusan ?? '-',
                'gender' => $loan->user->gender ?? '-',
                'judul_buku' => $loan->book->judul ?? 'Tidak diketahui',
                'tanggal_pinjam' => $loan->tanggal_pinjam,
                'tanggal_kembali' => $loan->tanggal_kembali,
            ];
        });

        return response()->json(['data' => $formatted]);
    }

    // ✅ Anggota meminjam buku
    public function borrow(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'book_id' => 'required|exists:books,id',
        ]);

        $loan = new Loan();
        $loan->user_id = $user->id;
        $loan->book_id = $request->book_id;
        $loan->tanggal_pinjam = Carbon::now();
        $loan->save();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dipinjam!',
            'data' => $loan,
        ]);
    }

    // ✅ Daftar buku yang sedang dipinjam
public function borrowed()
    {
        $pinjaman = Loan::with('book', 'user')
            ->whereNull('tanggal_kembali')
            ->get();

        $formatted = $pinjaman->map(function ($loan) {
            return [
                'id' => $loan->id,
                'nama_peminjam' => $loan->user->name ?? 'Tidak diketahui',
                'judul_buku' => $loan->book->judul ?? 'Tidak diketahui',
                'tanggal_pinjam' => $loan->tanggal_pinjam,
            ];
        });

        return response()->json(['data' => $formatted]);
    }

    // ✅ Mengembalikan buku
    public function returnBook(Request $request, $loanId)
    {
        $loan = Loan::find($loanId);

        if (!$loan) {
            return response()->json(['message' => 'Loan not found'], 404);
        }

        $loan->tanggal_kembali = now();
        $loan->save();

        return response()->json([
            'message' => 'Book returned successfully',
            'data' => $loan,
        ]);
    }

    // ✅ Riwayat user yang sedang login
    public function history()
    {
        $user = Auth::user();

        $riwayat = Loan::with('book')
            ->where('user_id', $user->id)
            ->orderBy('tanggal_pinjam', 'desc')
            ->get()
            ->map(function ($loan) {
                return [
                    'loan_id' => $loan->id,
                    'book_id' => $loan->book->id ?? null,
                    'judul_buku' => $loan->book->judul ?? 'Tidak diketahui',
                    'gambar' => $loan->book->gambar ?? null,
                    'tanggal_pinjam' => $loan->tanggal_pinjam,
                    'tanggal_kembali' => $loan->tanggal_kembali,
                ];
            });

        return response()->json(['data' => $riwayat]);
    }

    // ✅ Riwayat seluruh peminjaman (admin)
    public function historyAll()
    {
        $loans = Loan::with('book', 'user')->get();

        $formatted = $loans->map(function ($loan) {
            return [
                'id' => $loan->id,
                'nama' => $loan->user->name ?? 'Tidak diketahui',
                'jurusan' => $loan->user->jurusan ?? 'Tidak diketahui',
                'judul_buku' => $loan->book->judul ?? 'Tidak diketahui',
                'status' => $loan->tanggal_kembali ? 'Sudah Dikembalikan' : 'Belum Dikembalikan',
            ];
        });

        return response()->json(['data' => $formatted]);
    }

    // ✅ Admin menambahkan peminjaman manual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|integer',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after_or_equal:tanggal_pinjam',
            'user_id' => 'required|integer',
        ]);

        $loan = Loan::create([
            'book_id' => $validated['book_id'],
            'tanggal_pinjam' => $validated['tanggal_pinjam'],
            'tanggal_kembali' => $validated['tanggal_kembali'] ?? null,
            'user_id' => $validated['user_id'],
            'status' => 'borrowed',
        ]);

        return response()->json($loan, 201);
    }
}
