<?php 

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Illuminate\Database\Seeder;


class BookSeeder extends Seeder
{

public function run()
{
    // Nonaktifkan foreign key constraint dulu (untuk sementara)
    DB::statement('SET FOREIGN_KEY_CHECKS=0;');

    // Hapus data di tabel anak terlebih dahulu
    DB::table('loans')->truncate();

    // Lalu kosongkan tabel books
    Book::truncate();

    // Aktifkan kembali foreign key constraint
    DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    // Tambahkan data buku baru
    Book::create([
        'judul' => 'Dilan 1991',
        'penulis' => 'Pidi Baiq',
        'status' => 'tersedia',
        'category' => 'fiksi',
        'stok' => 5
    ]);
    Book::create([
        'judul' => 'Laskar Pelangi',
        'penulis' => 'Andrea Hirata',
        'status' => 'tersedia',
        'category' => 'fiksi',
        'stok' => 5
    ]);
    Book::create([
        'judul' => 'Negeri 5 Menara',
        'penulis' => 'Ahmad Fuadi',
        'status' => 'tersedia',
        'category' => 'fiksi',
        'stok' => 10
    ]);
    Book::create([
        'judul' => 'Bumi',
        'penulis' => 'Tere Liye',
        'status' => 'tersedia',
        'category' => 'fiksi',
        'stok' => 7
    ]);
    Book::create([
        'judul' => 'Membuka Rahasia Sains',
        'penulis' => 'Dr. Suryana',
        'status' => 'tersedia',
        'category' => 'non-fiksi',
        'stok' => 2
    ]);
    Book::create([
        'judul' => 'Sejarah Indonesia',
        'penulis' => 'Prof. S. Nugroho',
        'status' => 'tersedia',
        'category' => 'non-fiksi',
        'stok' => 3
    ]);
}
}