<?php

namespace App\Models;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Tag extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'slug',

        'description', 

        'cover_image_url',

        'uuid',
        'user_id',
    ];

    // pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // tiene muchos libros para relacionarse
    public function books(){
        return $this->belongsToMany(Book::class, 'book_tag')
                    ->withTimestamps();
    }
}
