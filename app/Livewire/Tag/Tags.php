<?php

namespace App\Livewire\Tag;

use App\Models\Tag;
use Livewire\Component;
use Livewire\WithPagination;

class Tags extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $perPage = 30;

    // propiedades del item
    public $tagId;

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
        $this->dispatch('tag-edit', $uuid); // llama al modelo de livewire para editar
    }

    // abrir modal para eliminar
    public function delete($uuid){
        $this->tagId = Tag::where('uuid', $uuid)->first()->id;

        \Flux\Flux::modal('delete-tag')->show();
    }

    // eliminar item
    public function destroy(){
        Tag::find($this->tagId)->delete();
        \Flux\Flux::modal('delete-tag')->close();
        session()->flash('success', 'Borrado correctamente');
        $this->redirectRoute('tags', navigate:true);
    }

    // render de pagina
    public function render()
    {
        $title = ['singular' => 'etiqueta', 'plural' => 'etiquetas'];

        $tags = Tag::select('id', 'name', 'slug', 'cover_image_url', 'uuid')            
            ->where(function ($query) {
                $query->where('name', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.tag.tags', compact(
            'title',
            'tags',
        ));
    }
}
