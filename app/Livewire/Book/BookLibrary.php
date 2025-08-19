<?php

namespace App\Livewire\Book;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class BookLibrary extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'id';
    public $sortDirection = 'asc';
    public $perPage = 10;
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
    public function render(){
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        // relaciones con el modelo
        $media_type_content = Book::media_type_content();
        $status_book = Book::status_book();
        $rating_stars = Book::rating_stars();
        $format_book = Book::format_book();

        $books = Book::where(function ($query) {
                        $query
                        ->where('title', 'like', "%{$this->search}%")
                        ->orWhere('slug', 'like', "%{$this->search}%")
                        ->orWhereHas('book_subjects', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        })
                        ->orWhereHas('book_collections', function ($q) {
                            $q->where('name', 'like', "%{$this->search}%");
                        });
            })
                        ->when($this->status_read, function( $query) {
                            return $query->where('status', $this->status_read);
                        })
                        ->when($this->tag_selected, function ($query) {
                            $query->whereHas('book_tags', function ($q) {
                                $q->where('tags.uuid', $this->tag_selected);
                            });
                        })
                        ->when($this->subject_selected, function ($query) {
                            $query->whereHas('book_subjects', function ($q) {
                                $q->where('subjects.uuid', $this->subject_selected);
                            });
                        })
                        ->when($this->genre_selected, function ($query) {
                            $query->whereHas('book_genres', function ($q) {
                                $q->where('genres.uuid', $this->genre_selected);
                            });
                        })
                        ->when($this->collection_selected, function ($query) {
                            $query->whereHas('book_collections', function ($q) {
                                $q->where('collections.uuid', $this->collection_selected);
                            });
                        })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
            
        return view('livewire.book.book-library', compact(
            'title',
            'books',

            'media_type_content',
            'status_book',
            'rating_stars',
            'format_book',
        ));
    }
}
