<?php

namespace App\Livewire\Book\Partials;

use App\Models\Book;
use App\Models\BookRead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BookStaticGenre extends Component
{
    public $genres;
    public $authors;
    public $books;
    public $books_total;

    public $year_start;
    public $year_end;

    public $format_book;
    public $category_book;
    public $language_book;
    public $rating_stars;

    public $books_total_pages = 0;

    public $pagesBuckets = [
        '📄 0-200' => 0,
        '📄 201-400' => 0,
        '📄 401-600' => 0,
        '📄 601-800' => 0,
        '📄 801-1000' => 0,
        '📄 1001-1200' => 0,
        '📄 1201-1400' => 0,
        '📄 1401-1600' => 0,
        '📄 1601-1800' => 0,
        '📄 1801-2000' => 0,
        '📄 2001 +' => 0,
        '📄 xxx' => 0,
    ];

    public function mount(){
        $this->year_start = Carbon::now()->format('Y');
        $this->year_end = Carbon::now()->format('Y');
        $this->category_book = Book::category_book();
        $this->language_book = Book::language_book();
        $this->rating_stars = Book::rating_stars();
        $this->format_book = Book::format_book();
    }

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

    public function render()
    {
        $year_start = $this->year_start;
        $year_end = $this->year_end;

        $reads = BookRead::where('user_id', Auth::id())->get();

        // Traés todos los libros del usuario (sin filtros por fecha)
        $this->books = Book::where('user_id', Auth::id())
            ->with('reads') // importante para evitar N+1
            ->get();

        $filteredBooks = $this->books->filter(function ($book) use ($year_start, $year_end) {
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

        $bookStats = $filteredBooks->map(function ($book) {
            return [
                'id' => $book->id,
                'title' => $book->title,
                'uuid' => $book->uuid,
            ];
        });


        $genreStats = $filteredBooks->flatMap(function ($book) {
            return $book->book_genres;
        })->groupBy('id')->map(function ($group) {
            return [
                'name' => $group->first()->name,
                'uuid' => $group->first()->uuid,
                'count' => $group->count(),
            ];
        });

        $authorStats = $filteredBooks->flatMap(function ($book) {
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

        $sagaStats = $filteredBooks->flatMap(function ($book) {
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
        

        // Obtener los años únicos usando Carbon
        $read_years = $reads->map(function($read) {
            return \Carbon\Carbon::parse($read->end_read)->year;
        })->unique()->sortDesc()->values()->toArray();

        foreach ($filteredBooks as $book) {
            $pages = $book->pages;

            if ($pages == '') $this->pagesBuckets['📄 xxx']++;
            elseif ($pages <= 200) $this->pagesBuckets['📄 0-200']++;
            elseif ($pages <= 400) $this->pagesBuckets['📄 201-400']++;
            elseif ($pages <= 600) $this->pagesBuckets['📄 401-600']++;
            elseif ($pages <= 800) $this->pagesBuckets['📄 601-800']++;
            elseif ($pages <= 1000) $this->pagesBuckets['📄 801-1000']++;
            elseif ($pages <= 1200) $this->pagesBuckets['📄 1001-1200']++;
            elseif ($pages <= 1400) $this->pagesBuckets['📄 1201-1400']++;
            elseif ($pages <= 1600) $this->pagesBuckets['📄 1401-1600']++;
            elseif ($pages <= 1800) $this->pagesBuckets['📄 1601-1800']++;
            elseif ($pages <= 2000) $this->pagesBuckets['📄 1801-2000']++;
            elseif ($pages > 2000) $this->pagesBuckets['📄 2001 +']++;
        }

        return view('livewire.book.partials.book-static-genre', compact(
            'read_years',
            'authorStats',
            'genreStats',
            'sagaStats',
            'bookStats',
            'filteredBooks',
        ));
    }
}
