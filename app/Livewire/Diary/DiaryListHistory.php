<?php

namespace App\Livewire\Diary;

use App\Models\Book;
use App\Models\Diary\Diary;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class DiaryListHistory extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'day';
    public $sortDirection = 'desc';
    public $perPage = 30;

    // propiedades del item
    public $diaryId;
    
    // relaciones con el modelo
    public 
    $humor_status;

    // refrescar paginacion
    public function updatingSearch(){$this->resetPage();}
    public function updatingSortField(){$this->resetPage();}
    public function updatingSortDirection(){$this->resetPage();}
    public function updatingPerPage(){$this->resetPage();}

    // funcion para ordenar la tabla
    public function sortBy($field){
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    public function render()
    {
        $title = ['singular' => 'diario', 'plural' => 'diarios'];

        // relaciones con el modelo
        $this->humor_status = Diary::humor_status();

        $diaries = Diary::where('user_id', Auth::id())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.diary.diary-list-history', compact(
            'title',
            'diaries',
        ));
    }
}
