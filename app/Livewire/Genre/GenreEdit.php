<?php

namespace App\Livewire\Genre;

use App\Models\Genre;
use Livewire\Component;
use Livewire\Attributes\On;

class GenreEdit extends Component
{
    // propiedades del item
    public $name, $description, $cover_image_url, $slug, $uuid, $user_id;
    public $genreId, $genre;

    // reglas de validacion
    protected function rules(){
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('genres', 'slug')->ignore($this->genre?->id ?? 0)],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('genres', 'uuid')->ignore($this->genre?->id)],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    // renombrar variables a castellano
    protected $validationAttributes = [
        'name' => 'nombre',
        'slug' => 'nombre url',
        'description' => 'descripcion',
        'cover_image_url' => 'imagen web',
        'uuid' => 'uuid',
        'user_id' => 'usuario',
    ];

    // recibe llamada al componente y activa la funcion edit
    #[On('genre-edit')]
    public function edit($uuid){
        $this->genre = Genre::where('uuid', $uuid)->first();

        $this->name = $this->genre->name; 
        $this->description = $this->genre->description; 
        $this->cover_image_url = $this->genre->cover_image_url; 
        $this->user_id = $this->genre->user_id; 
        $this->uuid = $this->genre->uuid; 

        \Flux\Flux::modal('edit-genre')->show();
    }

    // funcion para editar item
    public function update(){
        // datos automaticos
        $this->slug = \Illuminate\Support\Str::slug($this->name . '-' . \Illuminate\Support\Str::random(4));

        // validacion
        $validated_data = $this->validate();

        // actualizar dato
        $this->genre->update($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('edit-genre')->close();

        // mensaje de success
        session()->flash('success', 'Editado correctamente');

        // redireccionar
        $this->redirectRoute('genres', navigate:true);
    }

    // render de pagina
    public function render()
    {
        return view('livewire.genre.genre-edit');
    }
}
