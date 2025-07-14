<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $table = 'peminjaman'; // ✅ arahkan ke tabel 'peminjaman'

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class); // relasi ke user
    }

    public function book()
    {
        return $this->belongsTo(Book::class); // relasi ke buku
    }
}
