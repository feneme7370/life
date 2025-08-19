<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BookRead extends Model
{
    use HasFactory;
    protected $fillable = [
        'start_read',
        'end_read',

        'book_id',
        'user_id',
    ];

    // pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // pertenece a un libro
    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id', 'id');
    }
}
