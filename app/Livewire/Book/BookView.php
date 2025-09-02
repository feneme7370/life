<?php

namespace App\Livewire\Book;

use App\Models\Book;
use App\Models\BookRead;
use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
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
    $format_book,
    $language_book,
    $category_book;

    // propiedad para agregar nota
    public $quotes;
    public $reads;

    public $quoteId;
    public $quoteContent;
    public $readId;
    public $start_read;
    public $end_read;

    public function mount($uuid){
        $this->book = Book::where('uuid', $uuid)->with('book_tags')->first();
        $this->quotes = Quote::where('book_id', $this->book->id)->get();
        $this->reads = BookRead::where('book_id', $this->book->id)->get();
    }

    // abrir modal para editar
    public function edit($uuid){
        $this->dispatch('book-edit', $uuid); // llama al modelo de livewire para editar
    }

    // abrir modal de nota
    public function modalRead(){
        $this->start_read = '';
        $this->end_read = '';
        $this->modal('add-read')->show();
    }

    // agregar lectura
    public function addRead(){

        $this->validate([
            'start_read' => ['required', 'date'],
            'end_read' => ['nullable', 'date'],
        ]);

        BookRead::create([
            'user_id' => Auth::id(),
            'book_id' => $this->book->id,
            'start_read' => $this->start_read,
            'end_read' => $this->end_read ?? '',
        ]);

        $this->reads = BookRead::where('book_id', $this->book->id)->get();
        $this->start_read = '';
        $this->end_read = '';

        $this->modal('add-read')->close();

        session()->flash('success', 'Lectura agregada');
    }

    // abrir modal de nota
    public function modalQuote(){
        $this->quoteContent = '';
        $this->modal('add-quote')->show();
    }

    // modal para borrar nota
    public function deleteRead($id){
        $this->readId = $id;
   
        $this->modal('delete-read')->show();
        // \Flux\Flux::modal('delete-quote')->show();

    }

    // borrar nota
    public function destroyRead(){
        
        BookRead::find($this->readId)->delete();

        $this->modal('delete-read')->close();
        // \Flux\Flux::modal('delete-quote')->close();

        $this->reads = BookRead::where('book_id', $this->book->id)->get();
        $this->readId = '';

        session()->flash('success', 'Lecura eliminada');
    }


    // agregar nota
    public function addQuote(){

        $this->validate([
            'quoteContent' => ['required', 'string'],
            // 'quotePage' => ['nullable', 'numeric'],
        ]);

        Quote::create([
            'user_id' => Auth::id(),
            'book_id' => $this->book->id,
            'content' => $this->quoteContent,
        ]);

        $this->quotes = Quote::where('book_id', $this->book->id)->get();
        $this->quoteContent = '';

        $this->modal('add-quote')->close();
        // \Flux\Flux::modal('add-quotes')->close();
        
        // $this->quotePage = '';
        session()->flash('success', 'Cita agregada');
    }

    // modal para borrar nota
    public function deleteQuote($id){
        $this->quoteId = $id;
   
        $this->modal('delete-quote')->show();
        // \Flux\Flux::modal('delete-quote')->show();

    }

    // borrar nota
    public function destroyQuote(){
        
        Quote::find($this->quoteId)->delete();

        $this->modal('delete-quote')->close();
        // \Flux\Flux::modal('delete-quote')->close();

        $this->quotes = Quote::where('book_id', $this->book->id)->get();
        $this->quoteId = '';

        session()->flash('success', 'Cita eliminada');
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

        return view('livewire.book.book-view', compact(
            'title',
        ));
    }
}
