<?php

namespace App\Livewire\Diary;

use App\Models\Diary\Diary;
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
