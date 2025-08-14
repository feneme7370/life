<?php

namespace App\Livewire\Book;

use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class BookAuthorSearch extends Component
{
    public $query = '';
    public $authors = [];

    public function search()
    {
        if (strlen($this->query) < 2) {
            $this->authors = [];
            return;
        }

        $response = Http::get('https://openlibrary.org/search/authors.json', [
            'q' => $this->query
        ]);

        if ($response->ok()) {
            $data = $response->json();
            $this->authors = collect($data['docs'])->map(function ($author) {
                // Intentar parsear fecha a YYYY-MM-DD
                $formattedBirthDate = null;
                if (!empty($author['birth_date'])) {
                    try {
                        $formattedBirthDate = Carbon::parse($author['birth_date'])->format('Y-m-d');
                    } catch (\Exception $e) {
                        $formattedBirthDate = null; // Si falla el parseo, lo dejamos null
                    }
                }

                return [
                    'id' => str_replace('/authors/', '', $author['key']),
                    'name' => $author['name'] ?? 'Desconocido',
                    'picture' => "https://covers.openlibrary.org/a/olid/" . str_replace('/authors/', '', $author['key']) . "-M.jpg",
                    'birth_date' => $formattedBirthDate,
                    'bio' => is_array($author['bio'] ?? null) 
                        ? ($author['bio']['value'] ?? '') 
                        : ($author['bio'] ?? ''),
                ];
            })->toArray();
        }
    }

    // abrir modal para editar
    public function generate_autor($author){
        $author = json_decode($author);
        $this->dispatch('subject-create', $author); // llama al modelo de livewire para editar
    }
    
    public function render()
    {
        return view('livewire.book.book-author-search');
    }
}
