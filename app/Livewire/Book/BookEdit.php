<?php

namespace App\Livewire\Book;

use Flux\Flux;
use App\Models\Tag;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BookEdit extends Component
{
    // propiedades del item
    public 
    $title, 
    $slug, 
    $original_title, 

    // $start_date, 
    // $end_date, 
    // $start_date_two, 
    // $end_date_two, 
    // $start_date_three, 
    // $end_date_three, 

    $synopsis, 
    $release_date, 
    $number_collection, 
    $pages, 

    $summary, 
    $notes, 
    $is_favorite,

    $rating, 
    $format, 
    $media_type, 
    $status, 

    $cover_image, 
    $cover_image_url, 
    
    $uuid, 
    $user_id;

    public $bookId, $book;

    // relaciones con el modelo
    public 
    $media_type_content,
    $status_book,
    $rating_stars,
    $format_book;

    public $subjects, $genres, $tags, $collections;

    // propiedades para editar
    public $selected_book_genres = [];
    public $selected_book_tags = [];
    public $selected_book_subjects = [];
    public $selected_book_collections = [];

    // reglas de validacion
    protected function rules(){
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('books', 'slug')->ignore($this->book?->id ?? 0)],
            'original_title' => ['nullable', 'string', 'max:255'],
            
            // 'start_date' => ['nullable', 'date'],
            // 'end_date' => ['nullable', 'date'],
            // 'start_date_two' => ['nullable', 'date'],
            // 'end_date_two' => ['nullable', 'date'],
            // 'start_date_three' => ['nullable', 'date'],
            // 'end_date_three' => ['nullable', 'date'],
            
            'synopsis' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'number_collection' => ['nullable', 'integer', 'min:1'],
            'pages' => ['nullable', 'integer', 'min:1'],

            'summary' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_favorite' => ['nullable', 'numeric', 'min:0', 'max:1'],
            
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'format' => ['nullable', 'numeric', 'min:1'],
            'media_type' => ['nullable', 'numeric', 'min:1'],
            'status' => ['nullable', 'numeric', 'min:1'],
            
            'cover_image' => ['nullable', 'image', 'max:2048'], // si es archivo
            'cover_image_url' => ['nullable', 'url', 'max:65535'],

            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('books', 'uuid')->ignore($this->book?->id ?? 0)],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    // renombrar variables a castellano
    protected $validationAttributes = [
        'title' => 'titulo',
        'slug' => 'slug',
        'original_title' => 'titulo original',

        // 'start_date' => 'fecha de inicio',
        // 'end_date' => 'fecha de fin',
        // 'start_date_two' => 'fecha de inicio 2°',
        // 'end_date_two' => 'fecha de fin 2°',
        // 'start_date_three' => 'fecha de inicio 3°',
        // 'end_date_three' => 'fecha de fin 3°',
        
        'synopsis' => 'sinopsis',
        'release_date' => 'publicacion',
        'number_collection' => 'número de coleccion',
        'pages' => 'páginas',

        'summary' => 'resumen personal',
        'notes' => 'notas',
        'is_favorite' => 'favorito',
        
        'rating' => 'valoracion',
        'format' => 'formato',
        'media_type' => 'tipo de medio',
        'status' => 'estado',
        
        'cover_image' => 'imagen de portada',
        'cover_image_url' => 'URL de imagen de portada',

        'uuid' => 'UUID',
        'user_id' => 'usuario',
    ];

    // recibe llamada al componente y activa la funcion edit
    // #[On('book-edit')]
    // public function edit($uuid){
    public function mount($uuid){
        $this->book = Book::where('uuid', $uuid)->first();
        
        $this->title = $this->book->title; 
        $this->original_title = $this->book->original_title; 
        
        // $this->start_date = $this->book->start_date; 
        // $this->end_date = $this->book->end_date; 
        // $this->start_date_two = $this->book->start_date_two; 
        // $this->end_date_two = $this->book->end_date_two; 
        // $this->start_date_three = $this->book->start_date_three; 
        // $this->end_date_three = $this->book->end_date_three; 

        $this->synopsis = $this->book->synopsis; 
        $this->release_date = $this->book->release_date; 
        $this->number_collection = $this->book->number_collection; 
        $this->pages = $this->book->pages; 

        $this->summary = $this->book->summary; 
        $this->notes = $this->book->notes; 
        $this->is_favorite = $this->book->is_favorite ? true : false; 
        
        $this->rating = $this->book->rating; 
        $this->format = $this->book->format; 
        $this->media_type = $this->book->media_type; 
        $this->status = $this->book->status; 
        
        $this->cover_image = $this->book->cover_image; 
        $this->cover_image_url = $this->book->cover_image_url; 

        $this->user_id = $this->book->user_id; 
        $this->uuid = $this->book->uuid; 

        $this->selected_book_genres = $this->book->book_genres->pluck('id')->toArray();
        $this->selected_book_tags = $this->book->book_tags->pluck('id')->toArray();
        $this->selected_book_subjects = $this->book->book_subjects->pluck('id')->toArray();
        $this->selected_book_collections = $this->book->book_collections->pluck('id')->toArray();

        // \Flux\Flux::modal('edit-book')->show();
    }

    // funcion para editar item
    public function update(){
        // datos automaticos
        $this->slug = \Illuminate\Support\Str::slug($this->title . '-' . \Illuminate\Support\Str::random(4));
        $this->rating = $this->rating == '' ? 0 : $this->rating;
        $this->number_collection = $this->number_collection == '' ? 1 : $this->number_collection;
        $this->is_favorite = $this->is_favorite == true ? 1 : 0;

        // validacion
        $validated_data = $this->validate();

        // actualizar dato
        $this->book->update($validated_data);

        // asociar datos al libro
        $this->book->book_genres()->sync($this->selected_book_genres);
        $this->book->book_tags()->sync($this->selected_book_tags);
        $this->book->book_subjects()->sync($this->selected_book_subjects);
        $this->book->book_collections()->sync($this->selected_book_collections);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        // \Flux\Flux::modal('edit-book')->close();

        // mensaje de success
        session()->flash('success', 'Editado correctamente');

        // redireccionar
        $this->redirectRoute('books', navigate:true);
    }

    // render de pagina
    public function render()
    {
        // relaciones con el modelo
        $this->media_type_content = Book::media_type_content();
        $this->status_book = Book::status_book();
        $this->rating_stars = Book::rating_stars();
        $this->format_book = Book::format_book();

        // relaciones con otras tablas
        $this->subjects = Subject::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();
        $this->collections = Collection::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();
        $this->tags = Tag::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();
        $this->genres = Genre::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();

        return view('livewire.book.book-edit');
    }
}
