<?php

namespace App\Livewire\Genre;

use App\Models\Genre;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Genres extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 30;

    // propiedades del item
    public $genreId;

    // refrescar paginacion
    public function updatingSearch(){
        $this->resetPage();
    }

    // funcion para ordenar la tabla
    public function sortBy($field){
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
    }

    // abrir modal para editar
    public function edit($uuid){
        $this->dispatch('genre-edit', $uuid); // llama al modelo de livewire para editar
    }

    // abrir modal para eliminar
    public function delete($uuid){
        $this->genreId = Genre::where('uuid', $uuid)->first()->id;

        \Flux\Flux::modal('delete-genre')->show();
    }

    // eliminar item
    public function destroy(){
        Genre::find($this->genreId)->delete();
        \Flux\Flux::modal('delete-genre')->close();
        session()->flash('success', 'Borrado correctamente');
        $this->redirectRoute('genres', navigate:true);
    }

    public function export($table)
    {
        $data = \Illuminate\Support\Facades\DB::table($table)->where('user_id', Auth::id())->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\GenericExport($data, $table),
            "{$table}.xlsx"
        );
    }

    public function exportAsociation($table)
    {
        $data = \Illuminate\Support\Facades\DB::table($table)->get();

        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\GenericExport($data, $table),
            "{$table}.xlsx"
        );
    }

    protected function booksQuery()
    {
        return Genre::where('user_id', Auth::id())
            ->select('id', 'name', 'slug', 'cover_image_url', 'uuid')            
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection);
    }

    // render de pagina
    public function render()
    {
        $title = ['singular' => 'genero', 'plural' => 'generos'];

        $genres = $this->booksQuery()
            ->paginate($this->perPage);

        return view('livewire.genre.genres', compact(
            'title',
            'genres',
        ));
    }
}
