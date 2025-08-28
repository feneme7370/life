<?php

namespace App\Livewire\Collection;

use Livewire\Component;
use App\Models\Collection;
use Livewire\WithPagination;

class Collections extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 30;

    // propiedades del item
    public $collectionId;

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
        $this->dispatch('collection-edit', $uuid); // llama al modelo de livewire para editar
    }

    // abrir modal para eliminar
    public function delete($uuid){
        $this->collectionId = Collection::where('uuid', $uuid)->first()->id;

        \Flux\Flux::modal('delete-collection')->show();
    }

    // eliminar item
    public function destroy(){
        Collection::find($this->collectionId)->delete();
        \Flux\Flux::modal('delete-collection')->close();
        session()->flash('success', 'Borrado correctamente');
        $this->redirectRoute('collections', navigate:true);
    }

    // render de pagina
    public function render()
    {
        $title = ['singular' => 'coleccion', 'plural' => 'colecciones'];

        $collections = Collection::select('id', 'name', 'slug', 'cover_image_url', 'uuid')            
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.collection.collections', compact(
            'title',
            'collections',
        ));
    }
}
