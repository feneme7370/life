<?php

namespace App\Livewire\Tag;

use App\Models\Tag;
use Livewire\Component;
use Livewire\Attributes\On;

class TagEdit extends Component
{
    // propiedades del item
    public $name, $description, $cover_image_url, $slug, $uuid, $user_id;
    public $tagId, $tag;

    // reglas de validacion
    protected function rules(){
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tags', 'slug')->ignore($this->tag?->id ?? 0)],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('tags', 'uuid')->ignore($this->tag?->id)],
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
    #[On('tag-edit')]
    public function edit($uuid){
        $this->tag = Tag::where('uuid', $uuid)->first();
        
        $this->name = $this->tag->name; 
        $this->description = $this->tag->description; 
        $this->cover_image_url = $this->tag->cover_image_url; 
        $this->user_id = $this->tag->user_id; 
        $this->uuid = $this->tag->uuid; 

        \Flux\Flux::modal('edit-tag')->show();
    }

    // funcion para editar item
    public function update(){
        // datos automaticos
        $this->slug = \Illuminate\Support\Str::slug($this->name . '-' . \Illuminate\Support\Str::random(4));

        // validacion
        $validated_data = $this->validate();

        // actualizar dato
        $this->tag->update($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('edit-tag')->close();

        // mensaje de success
        session()->flash('success', 'Editado correctamente');

        // redireccionar
        $this->redirectRoute('tags', navigate:true);
    }

    // render de pagina
    public function render()
    {
        return view('livewire.tag.tag-edit');
    }
}
