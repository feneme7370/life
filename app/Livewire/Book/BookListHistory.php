<?php

namespace App\Livewire\Book;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;


class BookListHistory extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 5000;

    // propiedades del item
    public $bookId;
    
    // relaciones con el modelo
    public 
    $media_type_content,
    $status_book,
    $rating_stars,
    $format_book,
    $category_book,
    $language_book;

    // mostrar elementos al hacer click
    public $list_all_data = 'completa';

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
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        // relaciones con el modelo
        $this->media_type_content = Book::media_type_content();
        $this->status_book = Book::status_book();
        $this->rating_stars = Book::rating_stars();
        $this->format_book = Book::format_book();
        $this->category_book = Book::category_book();
        $this->language_book = Book::language_book();

        $books = Book::where('user_id', Auth::id())
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.book.book-list-history', compact(
            'title',
            'books',
        ));
    }
}
