<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    // Menampilkan semua buku
    public function index()
    {
        try {
            $books = Book::all();

            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // Menampilkan buku berdasarkan kategori (fiksi/non-fiksi)
    public function getBooksByCategory($category)
    {
        try {
            $books = Book::where('category', $category)->get(); // pastikan nama kolom di DB adalah 'category'

            return response()->json([
                'success' => true,
                'data' => $books
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    // Tambahkan di BookController
public function update(Request $request, $id)
{
    try {
        $book = Book::findOrFail($id);

        $book->judul = $request->judul;
        $book->penulis = $request->penulis;
        $book->category = $request->category;
        $book->stok = $request->stok;
        $book->save();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui',
            'data' => $book
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui buku: ' . $e->getMessage()
        ], 500);
    }
}
public function destroy($id)
{
    $book = Book::find($id);
    if (!$book) {
        return response()->json(['message' => 'Buku tidak ditemukan'], 404);
    }

    $book->delete();
    return response()->json(['message' => 'Buku berhasil dihapus'], 200);
}
public function store(Request $request)
{
    $validated = $request->validate([
        'judul' => 'required|string',
        'penulis' => 'required|string',
        'category' => 'required|string',
        'stok' => 'required|integer',
        'gambar' => 'nullable|string',
    ]);

    Book::create($validated);

    return response()->json(['message' => 'Buku berhasil ditambahkan'], 201);
}


}
