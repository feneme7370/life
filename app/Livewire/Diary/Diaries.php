<?php

namespace App\Livewire\Diary;

use Livewire\Component;
use App\Models\Diary\Diary;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use App\Models\Diary\DiaryImage;
use Illuminate\Support\Facades\Auth;

class Diaries extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'day';
    public $sortDirection = 'desc';
    public $perPage = 500;

    // propiedades del item
    public $diaryId;
    public $dayStart;
    public $dayEnd;

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

    public $highlightedDays = [];

    public function mount()
    {
        $this->highlightedDays = Diary::pluck('day')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y-m-d'))
            ->toArray();
        // dd($this->highlightedDays);
    }

    // abrir modal para editar
    public function edit($uuid){
        $this->dispatch('diary-edit', $uuid); // llama al modelo de livewire para editar
    }

    // abrir modal para eliminar
    public function delete($uuid){
        $this->diaryId = Diary::where('uuid', $uuid)->first()->id;
        \Flux\Flux::modal('delete-diary')->show();
    }

    public function deleteImage($path){

        if(\Illuminate\Support\Facades\File::exists($path)){
            \Illuminate\Support\Facades\File::delete($path);
        }
        DiaryImage::where('path', $path)->delete();
    }

    // eliminar item
    public function destroy(){
        $diary = Diary::find($this->diaryId);

        if($diary->images){
            foreach($diary->images as $image){
                $this->deleteImage($image->path);
            }
        }

        $diary->delete();
        \Flux\Flux::modal('delete-diary')->close();
        session()->flash('success', 'Borrado correctamente');
        // $this->redirectRoute('diaries', navigate:true);
    }


    #[On('reading-day-selected')]
    public function selectDay($date)
    {
        $this->dayStart = \Carbon\Carbon::parse($date)->format('Y-m-d');
        $this->dayEnd = \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
    public function clearDate(){
        $this->dayStart = \Carbon\Carbon::parse('1900-01-01')->format('Y-m-d');
        $this->dayEnd = \Carbon\Carbon::parse('2100-01-01')->format('Y-m-d');
    }

    // render de pagina
    public function render()
    {

        $title = ['singular' => 'diario', 'plural' => 'diarios'];

        $diaries = Diary::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                      ->orWhere('content', 'like', "%{$this->search}%")
                      ->orWhere('day', 'like', "%{$this->search}%");
            })
            ->when($this->dayStart !== null, function( $query) {
                return $query->where('day', '>=', $this->dayStart);
            })
            ->when($this->dayEnd !== null, function( $query) {
                return $query->where('day', '<=', $this->dayEnd);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);


        $humor_status = Diary::humor_status();

        return view('livewire.diary.diaries', compact(
            'title',
            'diaries',
            'humor_status',
        ));
    }
}
