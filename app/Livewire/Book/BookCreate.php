<?php

namespace App\Livewire\Book;

use App\Models\Tag;
use App\Models\Book;
use App\Models\Genre;
use App\Models\Subject;
use Livewire\Component;
use App\Models\BookRead;
use App\Models\Collection;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class BookCreate extends Component
{
    // propiedades del item
    public 
    $title, 
    $slug, 
    $original_title,

    $synopsis, 
    $release_date, 
    $number_collection, 
    $pages, 

    $summary, 
    $notes, 
    $is_favorite,

    $category = 0, 
    $rating = 0, 
    $format = 2, 
    $media_type = 1, 
    $status = 3, 
    $language = 0, 

    $cover_image, 
    $cover_image_url, 
    
    $uuid, 
    $user_id;

    public $start_read;
    public $end_read;

    // relaciones con el modelo
    public 
    $category_book,
    $media_type_content,
    $status_book,
    $rating_stars,
    $format_book,
    $language_book;

    public $subjects, $genres, $tags, $collections;

    // crear sujeto viniendo de API
    public $subject_name, $subject_slug, $subject_uuid, $subject_user_id;

    // propiedades para editar
    public $selected_book_genres = [];
    public $selected_book_tags = [];
    public $selected_book_subjects = [];
    public $selected_book_collections = [];

    public $author_name;
    public $collections_name;
    public $genres_name;
    public $tags_name;

    public $book_api = '';
    // reglas de validacion
    protected function rules(){
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('books', 'slug')->ignore($this->book?->id ?? 0)],
            'original_title' => ['nullable', 'string', 'max:255'],
            
            'synopsis' => ['nullable', 'string'],
            'release_date' => ['nullable', 'date'],
            'number_collection' => ['nullable', 'integer', 'min:1'],
            'pages' => ['nullable', 'integer', 'min:1'],

            'summary' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'is_favorite' => ['nullable', 'numeric', 'min:0', 'max:1'],

            'start_read' => ['nullable', 'date'],
            'end_read' => ['nullable', 'date'],
            
            'language' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'category' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'format' => ['nullable', 'numeric', 'min:1'],
            'media_type' => ['nullable', 'numeric', 'min:1'],
            'status' => ['nullable', 'numeric', 'min:1'],
            
            'cover_image' => ['nullable', 'image', 'max:2048'], // si es archivo
            'cover_image_url' => ['nullable', 'url', 'max:65535'],

            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('books', 'uuid')->ignore($this->book?->id ?? 0)],
            'user_id' => ['required', 'exists:users,id'],

            'author_name' => ['nullable', 'string', 'max:255'],
            'collections_name' => ['nullable', 'string', 'max:255'],
            'genres_name' => ['nullable', 'string', 'max:255'],
            'tags_name' => ['nullable', 'string', 'max:255'],
        ];
    }

    // renombrar variables a castellano
    protected $validationAttributes = [
        'title' => 'titulo',
        'slug' => 'slug',
        'original_title' => 'titulo original',
        
        'synopsis' => 'sinopsis',
        'release_date' => 'publicacion',
        'number_collection' => 'número de coleccion',
        'pages' => 'páginas',

        'summary' => 'resumen personal',
        'notes' => 'notas',
        'is_favorite' => 'favorito',

        'start_read' => 'inicio de lectura',
        'end_read' => 'fin de lectura',
        
        'language' => 'idioma',
        'category' => 'categoria',
        'rating' => 'valoracion',
        'format' => 'formato',
        'media_type' => 'tipo de medio',
        'status' => 'estado',
        
        'cover_image' => 'imagen de portada',
        'cover_image_url' => 'URL de imagen de portada',

        'uuid' => 'UUID',
        'user_id' => 'usuario',
    ];

    // funcion para crear item
    public function save(){
        // datos automaticos
        $this->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $this->slug = \Illuminate\Support\Str::slug($this->title . '-' . \Illuminate\Support\Str::random(4));
        $this->uuid = \Illuminate\Support\Str::random(24);
        $this->rating = $this->rating == '' ? 0 : $this->rating;
        $this->number_collection = $this->number_collection == '' ? 1 : $this->number_collection;
        $this->is_favorite = $this->is_favorite == true ? 1 : 0;
        $this->release_date = \Carbon\Carbon::parse($this->release_date.'-01-01');
        
        if(!$this->selected_book_subjects && $this->subject_name != ''){
            $author_created = Subject::firstOrCreate([
                'name' => $this->author_name,
                'slug' => \Illuminate\Support\Str::slug($this->author_name . '-' . \Illuminate\Support\Str::random(4)),
                'uuid' => \Illuminate\Support\Str::random(24),
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);
            $this->selected_book_subjects = [$author_created->id];
        }
        
        if(!$this->selected_book_collections && $this->collections_name != ''){
            $collections_created = Collection::firstOrCreate([
                'name' => $this->collections_name,
                'slug' => \Illuminate\Support\Str::slug($this->collections_name . '-' . \Illuminate\Support\Str::random(4)),
                'uuid' => \Illuminate\Support\Str::random(24),
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);
            $this->selected_book_collections = [$collections_created->id];
        }
        
        if(!$this->selected_book_genres && $this->genres_name != ''){
            $genres_created = Genre::firstOrCreate([
                'name' => $this->genres_name,
                'slug' => \Illuminate\Support\Str::slug($this->genres_name . '-' . \Illuminate\Support\Str::random(4)),
                'uuid' => \Illuminate\Support\Str::random(24),
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);
            $this->selected_book_genres = [$genres_created->id];
        }
        
        if(!$this->selected_book_tags && $this->tags_name != ''){
            $tags_created = Tag::firstOrCreate([
                'name' => $this->tags_name,
                'slug' => \Illuminate\Support\Str::slug($this->tags_name . '-' . \Illuminate\Support\Str::random(4)),
                'uuid' => \Illuminate\Support\Str::random(24),
                'user_id' => \Illuminate\Support\Facades\Auth::user()->id,
            ]);
            $this->selected_book_tags = [$tags_created->id];
        }

        // validacion
        $validated_data = $this->validate();

        // $this->validate([
        //     'start_read' => ['required', 'date'],
        //     'end_read' => ['nullable', 'date'],
        // ]);

        // crear dato
        $book = Book::create($validated_data);

        // asociar datos al libro
        $book->book_genres()->sync($this->selected_book_genres);
        $book->book_tags()->sync($this->selected_book_tags);
        $book->book_subjects()->sync($this->selected_book_subjects);
        $book->book_collections()->sync($this->selected_book_collections);

        BookRead::create([
            'user_id' => Auth::id(),
            'book_id' => $book->id,
            'start_read' => $this->start_read,
            'end_read' => $this->end_read,
        ]);

        // resetear propiedades
        $this->reset();

        // mensaje de success
        session()->flash('success', 'Creado correctamente');

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
        $this->category_book = Book::category_book();
        $this->language_book = Book::language_book();

        // relaciones con otras tablas
        $this->subjects = Subject::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();
        $this->collections = Collection::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();
        $this->tags = Tag::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();
        $this->genres = Genre::where('user_id', Auth::user()->id)->orderBy('name', 'ASC')->get();

        return view('livewire.book.book-create');
    }
}
