<?php

namespace App\Livewire\Dashboard;

use App\Models\Book;
use App\Models\BookRead;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DashboardIndex extends Component
{
    public $books;
    public $reads;

    public function mount(){
        $this->books = Book::where('user_id', Auth::id())->with('reads')->get();
        $this->reads = BookRead::where('user_id', Auth::id())->with('books')->get();
    }

    public function render()
    {
        return view('livewire.dashboard.dashboard-index');
    }
}
