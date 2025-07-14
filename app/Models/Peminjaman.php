<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $fillable = [
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'user_id',
        'status',
    ];
}
