<?php

namespace App\Livewire\Genre;

use App\Models\Genre;
use Livewire\Component;

class GenreCreate extends Component
{
    // propiedades del item
    public 
    $name, 
    $description, 
    $cover_image_url, 
    $slug, 
    $uuid, 
    $user_id;

    // reglas de validacion
    protected function rules(){
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('genres', 'slug')->ignore($this->genre?->id ?? 0)],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('genres', 'uuid')->ignore($this->genre?->id ?? 0)],
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

    // funcion para crear item
    public function save(){
        // datos automaticos
        $this->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $this->slug = \Illuminate\Support\Str::slug($this->name . '-' . \Illuminate\Support\Str::random(4));
        $this->uuid = \Illuminate\Support\Str::random(24);

        // validacion
        $validated_data = $this->validate();

        // crear dato
        Genre::create($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('create-genre')->close();

        // mensaje de success
        session()->flash('success', 'Creado correctamente');

        // redireccionar
        $this->redirectRoute('genres', navigate:true);
    }
    
    // render de pagina
    public function render()
    {
        return view('livewire.genre.genre-create');
    }
}
