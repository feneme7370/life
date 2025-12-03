<?php

namespace App\Livewire\Book;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Books extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 500;

    // propiedades del item
    public $bookId;

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

    // abrir modal para editar
    // public function edit($uuid){
    //     $this->dispatch('book-edit', $uuid); // llama al modelo de livewire para editar
    // }

    // abrir modal para eliminar
    public function delete($uuid){
        $this->bookId = Book::where('uuid', $uuid)->first()->id;

        \Flux\Flux::modal('delete-book')->show();
    }

    // eliminar item
    public function destroy(){
        Book::find($this->bookId)->delete();
        \Flux\Flux::modal('delete-book')->close();
        session()->flash('success', 'Borrado correctamente');
        $this->redirectRoute('books', navigate:true);
    }

    // render de pagina
    public function render()
    {
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        // relaciones con el modelo
        $media_type_content = Book::media_type_content();
        $status_book = Book::status_book();
        $rating_stars = Book::rating_stars();
        $format_book = Book::format_book();
        $category_book = Book::category_book();

        $books = Book::where('user_id', Auth::id())
            ->where(function ($query) {
                $query->where('title', 'like', "%{$this->search}%")
                      ->orWhere('slug', 'like', "%{$this->search}%");
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.book.books', compact(
            'title',
            'books',

            'media_type_content',
            'status_book',
            'rating_stars',
            'format_book',
            'category_book',
        ));
    }
}
