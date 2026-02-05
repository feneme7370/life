<?php

namespace App\Livewire\Book;


use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class BookLibrary extends Component
{
    // paginacion
    use WithPagination;

    // propiedades para paginacion y orden
    public $search = '';
    public $sortField = 'title';
    public $sortDirection = 'desc';
    public $perPage = 500;
    public $status_read = "", $collection_selected, $subject_selected, $tag_selected, $genre_selected, $star_selected, $language_selected, $category_selected, $format_selected;

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
        'star_selected' => [ 'as' => 'star' ],
        'language_selected' => [ 'as' => 'language' ],
        'category_selected' => [ 'as' => 'category' ],
        'format_selected' => [ 'as' => 'format' ],
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

    public function export()
    {
        $books = $this->booksQuery()
            ->get(); // 👈 sin paginación

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\BookLibraryExport($books), 'BooksLibraryExport.xlsx');

    }

    protected function booksQuery()
    {
        return Book::where('user_id', Auth::id())
        
            // no abandonado
            ->where('status', '!=', 5)

            ->whereHas('reads')
            ->withMax('reads', 'end_read')
            ->whereHas('reads', fn($q) => $q->where('end_read', '<>' ,''))

            ->when($this->status_read, function( $query) {
                return $query->where('status', $this->status_read);
            })
            ->when($this->star_selected !== null, function( $query) {
                return $query->where('rating', $this->star_selected);
            })
            ->when($this->language_selected !== null, function( $query) {
                return $query->where('language', $this->language_selected);
            })
            ->when($this->category_selected !== null, function( $query) {
                return $query->where('category', $this->category_selected);
            })
            ->when($this->format_selected !== null, function( $query) {
                return $query->where('format', $this->format_selected);
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

            ->orderBy('reads_max_end_read', $this->sortDirection);
    }

    public function render(){
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        // relaciones con el modelo
        $media_type_content = Book::media_type_content();
        $status_book = Book::status_book();
        $rating_stars = Book::rating_stars();
        $format_book = Book::format_book();


        // traer libros no abandonados y entre fechas ordenados por la cosulta
        // $filteredBooks = $this->books->where('status', '!=', 5)
        // ;
        
        // $books = Book::query()
        //     ->where('user_id', Auth::id())
        
        //     // no abandonado
        //     ->where('status', '!=', 5)

        //     ->whereHas('reads')
        //     ->withMax('reads', 'end_read')
        //     ->whereHas('reads', fn($q) => $q->where('end_read', '<>' ,''))

        //     ->when($this->status_read, function( $query) {
        //         return $query->where('status', $this->status_read);
        //     })
        //     ->when($this->star_selected !== null, function( $query) {
        //         return $query->where('rating', $this->star_selected);
        //     })
        //     ->when($this->language_selected !== null, function( $query) {
        //         return $query->where('language', $this->language_selected);
        //     })
        //     ->when($this->category_selected !== null, function( $query) {
        //         return $query->where('category', $this->category_selected);
        //     })
        //     ->when($this->format_selected !== null, function( $query) {
        //         return $query->where('format', $this->format_selected);
        //     })
        //     ->when($this->tag_selected, function ($query) {
        //         $query->whereHas('book_tags', function ($q) {
        //             $q->where('tags.uuid', $this->tag_selected);
        //         });
        //     })
        //     ->when($this->subject_selected, function ($query) {
        //         $query->whereHas('book_subjects', function ($q) {
        //             $q->where('subjects.uuid', $this->subject_selected);
        //         });
        //     })
        //     ->when($this->genre_selected, function ($query) {
        //         $query->whereHas('book_genres', function ($q) {
        //             $q->where('genres.uuid', $this->genre_selected);
        //         });
        //     })
        //     ->when($this->collection_selected, function ($query) {
        //         $query->whereHas('book_collections', function ($q) {
        //             $q->where('collections.uuid', $this->collection_selected);
        //         });
        //     })

        //     ->orderBy('reads_max_end_read', $this->sortDirection)

            // ->paginate($this->perPage);

        $books = $this->booksQuery()
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
