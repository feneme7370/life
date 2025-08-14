<?php

namespace App\Livewire\Book;

use App\Models\Book;
use Livewire\Component;

class BookView extends Component
{
    ///////////////////////////// MODULO VARIABLES /////////////////////////////
    public $book;

    // relaciones con el modelo
    public 
    $media_type_content,
    $status_book,
    $rating_stars,
    $format_book;

    public function mount($uuid){
        $this->book = Book::where('uuid', $uuid)->with('book_tags')->first();
    }
    
    public function render()
    {
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        // relaciones con el modelo
        $this->media_type_content = Book::media_type_content();
        $this->status_book = Book::status_book();
        $this->rating_stars = Book::rating_stars();
        $this->format_book = Book::format_book();

        return view('livewire.book.book-view', compact(
            'title'
        ));
    }
}
