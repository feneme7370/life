<?php

namespace App\Livewire\Book;

use Livewire\Component;

class BookCreateApi extends Component
{
    public function render()
    {
        $title = ['singular' => 'libro', 'plural' => 'libros'];

        return view('livewire.book.book-create-api', compact(
            'title',
        ));
    }
}
