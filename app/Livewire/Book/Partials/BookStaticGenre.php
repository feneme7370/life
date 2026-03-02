<?php

namespace App\Livewire\Book\Partials;

use App\Models\Book;
use App\Models\BookRead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookStaticGenre extends Component
{
    // properties
    public $genres;
    public $authors;

    // data books
    public $books;
    public $books_total;

    // properties to filter date
    public $year_start;
    public $year_end;

    // book model
    public $format_book;
    public $category_book;
    public $language_book;
    public $rating_stars;

    public $abandonatedBooks;
    public $pendingBooks;
    public $pendingComments;
    public $read_years;

    public $authorStats;
    public $genreStats;
    public $sagaStats;
    public $bookStats;

    // count pages total
    public $books_total_pages = 0;

    // category to pages
    public $pagesBuckets = [
        '📄 0-250' => 0,
        '📄 251-500' => 0,
        '📄 501-750' => 0,
        '📄 751-1000' => 0,
        '📄 1001 +' => 0,
        '📄 xxx' => 0,
    ];

    // mount data
    public function mount(){
        // year today
        $this->year_start = Carbon::now()->format('Y');
        $this->year_end = Carbon::now()->format('Y');

        // book categories default
        $this->category_book = Book::category_book();
        $this->language_book = Book::language_book();
        $this->rating_stars = Book::rating_stars();
        $this->format_book = Book::format_book();

        // Traés todos los libros del usuario (sin filtros por fecha)
        $this->books = Book::where('user_id', Auth::id())
        ->orderBy('title', 'asc')
            ->with('reads') // importante para evitar N+1
            ->get();
    }

    // chage new year filter
    public function newYear($value){
        if($value === 'todo'){
            $this->year_start = Carbon::parse('1900-01-01')->format('Y');
            $this->year_end = Carbon::parse('2100-01-01')->format('Y');
            $this->reset('pagesBuckets');
        }else{
            $this->year_start = $value;
            $this->year_end = $value;
            $this->reset('pagesBuckets');
        }
    }

    // render page
    public function render()
    {
        // only to use function filter
        $year_start = $this->year_start;
        $year_end = $this->year_end;

        // Sacar los años únicos para el filtro
        $this->read_years = $this->books
            ->pluck('reads')               // me quedo solo con las colecciones de reads
            ->flatten()                    // aplanar todo en una sola colección
            ->pluck('end_read')            // me quedo con las fechas end_read
            ->filter()                     // saco nulos
            ->map(fn($date) => \Carbon\Carbon::parse($date)->year) // paso a año
            ->unique()                     // elimino duplicados
            ->sortDesc()                       // ordeno los años
        ->values();                    // limpio los índices

        // traer libros no abandonados y entre fechas ordenados por la cosulta
        $filteredBooks = $this->books
            ->where('status', '!=', 5)
            ->filter(function ($book) use ($year_start, $year_end) {

                return $book->reads->contains(function ($read) use ($year_start, $year_end) {
                    $year = (int) substr($read->end_read, 0, 4); // más rápido que Carbon

                    return
                        (!$year_start || $year >= $year_start) &&
                        (!$year_end   || $year <= $year_end);
                });

            });

        // Generar todos los meses vacíos (de 01 a 12) con cantidad de libros por mes
        $months = collect(range(1, 12))->mapWithKeys(fn ($m) => [str_pad($m, 2, '0', STR_PAD_LEFT) => 0]);
        $filteredBooksMonths = $this->books
            ->where('status', '!=', 5)
            ->flatMap(function ($book) use ($year_start, $year_end) {
                return $book->reads
                    // ->filter(fn ($read) => $read->end_read && Carbon::parse($read->start_read)->year >= $year_start && Carbon::parse($read->end_read)->year <= $year_end)
                    ->filter(fn ($read) => $read->end_read && Carbon::parse($read->end_read)->year == $year_end)
                    ->map(fn ($read) => \Carbon\Carbon::parse($read->end_read)->format('m'));
            })
            ->countBy()
            ->union($months) // completa los que faltan
            ->sortKeys();    // ordena de 01 a 12;

        // libros leidos
        $this->bookStats = $filteredBooks->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'uuid' => $book->uuid,
            ];
        });

        // generos leidos
        $this->genreStats = $filteredBooks->flatMap(function ($book) {
                return $book->book_genres;
            })->groupBy('id')->map(function ($group) {
                return [
                    'name' => $group->first()->name,
                    'uuid' => $group->first()->uuid,
                    'count' => $group->count(),
                ];
            }
        );

        // autores leidos
        $this->authorStats = $filteredBooks->flatMap(function ($book) {
                return $book->book_subjects;
            })->groupBy('id')->map(function ($group) {
                return [
                    'name' => $group->first()->name,
                    'uuid' => $group->first()->uuid,
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count') // si querés los más leídos primero
        ->take(100);

        // sagas leidas
        $this->sagaStats = $filteredBooks->flatMap(function ($book) {
                return $book->book_collections;
            })->groupBy('id')->map(function ($group) {
                return [
                    'name' => $group->first()->name,
                    'uuid' => $group->first()->uuid,
                    'count' => $group->count(),
                ];
            })
            ->sortByDesc('count') // si querés los más leídos primero
        ->take(100);

        $this->books_total_pages = $filteredBooks->sum(fn ($book) => (int) $book->pages);
        
        $this->pendingComments = $filteredBooks->filter(fn($book) => !$book->notes);
        
        $this->pendingBooks = $this->books->filter(fn($book) => !$book->reads->count());

        $this->abandonatedBooks = $this->books
                    ->filter(fn($book) => $book->status === 5)
                    ->filter(function ($book) use ($year_start, $year_end) {
                        return $book->reads->contains(function ($read) use ($year_start, $year_end) {
                            $endYear = \Carbon\Carbon::parse($read->end_read)->year;

                            // Si están seteados los dos límites
                            if ($year_start && $year_end) {
                                return $endYear >= $year_start && $endYear <= $year_end;
                            }

                            // Si solo está el inicio
                            if ($year_start) {
                                return $endYear >= $year_start;
                            }

                            // Si solo está el fin
                            if ($year_end) {
                                return $endYear <= $year_end;
                            }

                            return true;
                        });
                    });

        // clasificar por cantidadd de paginas los libros leidos de la fecha
        foreach ($filteredBooks as $book) {
            $pages = $book->pages;

            if ($pages == '') $this->pagesBuckets['📄 xxx']++;
            elseif ($pages <= 250) $this->pagesBuckets['📄 0-250']++;
            elseif ($pages <= 500) $this->pagesBuckets['📄 251-500']++;
            elseif ($pages <= 750) $this->pagesBuckets['📄 501-750']++;
            elseif ($pages <= 1000) $this->pagesBuckets['📄 751-1000']++;
            elseif ($pages > 1000) $this->pagesBuckets['📄 1001 +']++;
        }

        return view('livewire.book.partials.book-static-genre', compact(
            'filteredBooks',
            'filteredBooksMonths',
        ));
    }
}
