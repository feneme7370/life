<?php

namespace App\Livewire\Book;

use App\Models\Book;
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
    $format_book;

    // propiedad para agregar nota
    public $quotes;
    public $quoteId;
    public $quoteContent;
    // public $quotePage;

    public function mount($uuid){
        $this->book = Book::where('uuid', $uuid)->with('book_tags')->first();
        $this->quotes = Quote::where('book_id', $this->book->id)->get();
    }

    // abrir modal para editar
    public function edit($uuid){
        $this->dispatch('book-edit', $uuid); // llama al modelo de livewire para editar
    }

    // abrir modal de nota
    public function modalQuote(){
        $this->quoteContent = '';
        $this->modal('add-quotes')->show();
        // \Flux\Flux::modal('add-quotes')->show();
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

        $this->modal('add-quotes')->close();
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

        return view('livewire.book.book-view', compact(
            'title'
        ));
    }
}
