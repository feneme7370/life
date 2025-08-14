<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'original_title',

        'start_date',
        'end_date',
        'start_date_two',
        'end_date_two',
        'start_date_three',
        'end_date_three',
        
        'synopsis',
        'release_date',
        'number_collection',
        'pages',
        
        'summary',
        'notes',
        'is_favorite',
        
        'rating',
        'format',
        'media_type',
        'status',       

        'cover_image',
        'cover_image_url',

        'uuid',
        'user_id',
    ];

    // pertenece a un usuario
    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // tiene muchos tags para relacionarse
    public function book_tags(){
        return $this->belongsToMany(Tag::class, 'book_tag')
                    ->withTimestamps();
    }

    // tiene muchos subjects para relacionarse
    public function book_subjects(){
        return $this->belongsToMany(Subject::class, 'book_subject')
                    ->withTimestamps();
    }

    // tiene muchos collections para relacionarse
    public function book_collections()    {
        return $this->belongsToMany(Collection::class, 'book_collection')
                    ->withTimestamps();
    }

    // tiene muchos genres para relacionarse
    public function book_genres()    {
        return $this->belongsToMany(Genre::class, 'book_genre')
                    ->withTimestamps();
    }

    // valoraciones en estrellas para cada libro
    public static function rating_stars(){
        return [
            0 => 'Sin Valoracion', 
            1 => '⭐', 
            2 => '⭐⭐', 
            3 => '⭐⭐⭐', 
            4 => '⭐⭐⭐⭐', 
            5 => '⭐⭐⭐⭐⭐'
        ];
    }

    // tipo de libro
    public static function media_type_content(){
        return [
            1 => '📖 Libro', 
            2 => '📚 Manga'
        ];
    }
    
    // estados de lectura del libro
    public static function status_book(){
        return [
            1 => '📌 Quiero leer', 
            2 => '✅ Leído', 
            3 => '📖 Leyendo', 
            4 => '🔁 Releído', 
            5 => '🚫 Abandonado'
        ];
    }
    
    // formato del libro
    public static function format_book(){
        return [
            1 => '📖 Fisico', 
            2 => '💻 Digital', 
            3 => '🎧 Audiolibro'
        ];
    }
    
    // public static function title()
    // {
    //     return 'Biblioteca 📚';
    // }
}
