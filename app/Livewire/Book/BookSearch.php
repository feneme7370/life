<?php

namespace App\Livewire\Book;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BookSearch extends Component
{
    public $query = '';
    public $results = [];

    public function search()
    {
        $this->results = [];

        if (strlen($this->query) < 3) return;

        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $this->query,
            'maxResults' => 12,
            'printType' => 'books',
        ]);

        if (!$response->ok() || empty($response['items'])) return;

        foreach ($response['items'] as $item) {
            $info = $item['volumeInfo'] ?? [];

            $book = [
                'title' => $info['title'] ?? '',
                'authors' => $info['authors'] ?? [],
                'description' => $info['description'] ?? '',
                'published_date' => $info['publishedDate'] ?? '',
                'pages' => $info['pageCount'] ?? null,
                'categories' => $info['categories'] ?? [],
                'language' => $info['language'] ?? '',
                'isbn_10' => null,
                'isbn_13' => null,
                'thumbnail' => $info['imageLinks']['thumbnail'] ?? null,
            ];

            // Revisar identificadores ISBN
            if (isset($info['industryIdentifiers'])) {
                foreach ($info['industryIdentifiers'] as $id) {
                    if ($id['type'] === 'ISBN_10') $book['isbn_10'] = $id['identifier'];
                    if ($id['type'] === 'ISBN_13') $book['isbn_13'] = $id['identifier'];
                }
            }

            $this->results[] = $book;
        }
    }

    // abrir modal para editar
    public function generate($book){
        $book = json_decode($book);
        $this->redirectRoute('book_create', $book, navigate:true);
        // $this->dispatch('book-create', $book); // llama al modelo de livewire para editar
    }


    public function render()
    {
        return view('livewire.book.book-search');
    }
}
