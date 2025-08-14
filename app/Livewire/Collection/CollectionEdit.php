<?php

namespace App\Livewire\Collection;

use Livewire\Component;
use App\Models\Collection;
use Livewire\Attributes\On;

class CollectionEdit extends Component
{
    // propiedades del item
    public $name, $description, $cover_image_url, $slug, $uuid, $user_id;
    public $collectionId, $collection;

    // reglas de validacion
    protected function rules(){
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('collections', 'slug')->ignore($this->collection?->id ?? 0)],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('collections', 'uuid')->ignore($this->collection?->id)],
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
    #[On('collection-edit')]
    public function edit($uuid){
        $this->collection = Collection::where('uuid', $uuid)->first();
        
        $this->name = $this->collection->name; 
        $this->description = $this->collection->description; 
        $this->cover_image_url = $this->collection->cover_image_url; 
        $this->user_id = $this->collection->user_id; 
        $this->uuid = $this->collection->uuid; 

        \Flux\Flux::modal('edit-collection')->show();
    }

    // funcion para editar item
    public function update(){
        // datos automaticos
        $this->slug = \Illuminate\Support\Str::slug($this->name . '-' . \Illuminate\Support\Str::random(4));

        // validacion
        $validated_data = $this->validate();

        // actualizar dato
        $this->collection->update($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('edit-collection')->close();

        // mensaje de success
        session()->flash('success', 'Editado correctamente');

        // redireccionar
        $this->redirectRoute('collections', navigate:true);
    }

    // render de pagina
    public function render()
    {
        return view('livewire.collection.collection-edit');
    }
}
