<?php

namespace App\Livewire\Diary;

use App\Models\Diary\Diary;
use App\Models\Diary\DiaryImage;
use Livewire\Component;

class DiaryView extends Component
{
    ///////////////////////////// MODULO VARIABLES /////////////////////////////
    public $diary;

    // relaciones con el modelo
    public 
    $humor_status;

    public function mount($uuid){
        $this->diary = Diary::where('uuid', $uuid)->first();
    }

    public function deleteImage($path){

        if(\Illuminate\Support\Facades\File::exists($path)){
            \Illuminate\Support\Facades\File::delete($path);
        }
        DiaryImage::where('path', $path)->delete();
    }

    public function render()
    {
        $title = ['singular' => 'diario', 'plural' => 'diarios'];


        // relaciones con el modelo
        $this->humor_status = Diary::humor_status();

        return view('livewire.diary.diary-view', compact(
            'title',
        ));
    }
}
