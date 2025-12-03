<?php

namespace App\Models\Diary;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Diary extends Model
{
    use HasFactory;
    protected $fillable = [
        'day',
        'humor',

        'title', 
        'content',

        'uuid',
        'user_id',
    ];

    // tiene muchas imagenes
    public function images() {
        return $this->hasMany(DiaryImage::class);
    }

    // pertenece a un usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // valoraciones en estrellas para cada libro
    public static function humor_status(){
        return [
            0 => 'Alegre', 
            1 => 'Normal', 
            2 => 'Enojado', 
            3 => 'Feliz', 
            4 => 'Triste', 
            5 => 'Emocionado'
        ];
    }
}
