<?php

namespace App\Livewire\Book;

use App\Models\Book;
use Livewire\Component;
use App\Models\BookRead;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class BookHistory extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'end_read';
    public $sortDirection = 'desc';
    public $perPage = 30;
    public $status_read = "", $collection_selected, $subject_selected, $tag_selected, $genre_selected;

    // propiedades del item
    public $bookId;

    // mostrar variables en queryString
    protected function queryString(){
        return [
        'search' => [ 'as' => 'q' ],
        'status_read' => [ 'as' => 'r' ],
        'collection_selected' => [ 'as' => 'c' ],
        'subject_selected' => [ 'as' => 'a' ],
        'tag_selected' => [ 'as' => 't' ],
        'genre_selected' => [ 'as' => 'g' ],
        ];
    }

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
    
    public function render(){
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        // relaciones con el modelo
        $media_type_content = Book::media_type_content();
        $status_book = Book::status_book();
        $rating_stars = Book::rating_stars();
        $format_book = Book::format_book();

        $reads = BookRead::where('user_id', Auth::id())
            ->with('book') // para traer el libro asociado
            ->whereHas('book', function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('slug', 'like', "%{$this->search}%")
                            ->orWhereHas('book_subjects', function ($q) {
                  $q->where('name', 'like', "%{$this->search}%");
              })
            ->orWhereHas('book_collections', function ($q) {
                  $q->where('name', 'like', "%{$this->search}%");
              });
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $books_to_reads = Book::with('reads')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        $books_to_comments = Book::with('reads')
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.book.book-history', compact(
            'title',
            'reads',
            'books_to_reads',
            'books_to_comments',

            'media_type_content',
            'status_book',
            'rating_stars',
            'format_book',
        ));
    }
}
