<?php

namespace App\Models\Diary;

use App\Models\Diary\Diary;
use Illuminate\Database\Eloquent\Model;

class DiaryImage extends Model
{
    protected $fillable = ['diary_id', 'path'];

    public function diary() {
        return $this->belongsTo(Diary::class);
    }
}
