<?php

use App\Livewire\Book\BookCreate;
use App\Livewire\Book\BookCreateApi;
use App\Livewire\Book\BookEdit;
use App\Livewire\Book\BookHistory;
use App\Livewire\Book\BookLibrary;
use App\Livewire\Book\Books;
use App\Livewire\Book\BookView;
use App\Livewire\Collection\Collections;
use App\Livewire\Genre\Genres;
use App\Livewire\Settings\Profile;
use App\Livewire\Subject\Subjects;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Appearance;
use App\Livewire\Tag\Tags;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('libros', Books::class)->name('books');
    Route::get('libro/{uuid}', BookView::class)->name('book_view');
    Route::get('libro', BookCreate::class)->name('book_create');
    Route::get('libro/{uuid}/edit', BookEdit::class)->name('book_edit');
    Route::get('libreria', BookLibrary::class)->name('books_library');
    Route::get('libros_historial', BookHistory::class)->name('books_history');
    Route::get('libros_api', BookCreateApi::class)->name('book_create_api');
    
    Route::get('sujetos', Subjects::class)->name('subjects');
    Route::get('generos', Genres::class)->name('genres');
    Route::get('etiquetas', Tags::class)->name('tags');
    Route::get('colecciones', Collections::class)->name('collections');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

require __DIR__.'/auth.php';
