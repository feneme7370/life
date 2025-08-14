<?php

namespace App\Livewire\Tag;

use App\Models\Tag;
use Livewire\Component;

class TagCreate extends Component
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
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tags', 'slug')->ignore($this->tag?->id ?? 0)],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tags', 'uuid')->ignore($this->tag?->id ?? 0)],
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
        Tag::create($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('create-tag')->close();

        // mensaje de success
        session()->flash('success', 'Creado correctamente');

        // redireccionar
        $this->redirectRoute('tags', navigate:true);
    }
    
    // render de pagina
    public function render()
    {
        return view('livewire.tag.tag-create');
    }
}
