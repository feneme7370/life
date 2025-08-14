<?php

namespace App\Livewire\Subject;

use App\Models\Subject;
use Livewire\Component;
use Livewire\Attributes\On;

class SubjectCreate extends Component
{
    // propiedades del item
    public 
    $name,
    $birthdate,
    $country, 
    $description, 
    $cover_image_url, 
    $slug, 
    $uuid, 
    $user_id;

    // reglas de validacion
    protected function rules(){
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('subjects', 'slug')->ignore($this->subject?->id ?? 0)],
            'birthdate' => ['nullable', 'date'],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('subjects', 'uuid')->ignore($this->subject?->id ?? 0)],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    // renombrar variables a castellano
    protected $validationAttributes = [
        'name' => 'nombre',
        'slug' => 'nombre url',
        'birthdate' => 'fecha de nacimiento',
        'country' => 'pais',
        'description' => 'descripcion',
        'cover_image_url' => 'imagen web',
        'uuid' => 'uuid',
        'user_id' => 'usuario',
    ];

    // recibe llamada al componente y activa la funcion generate de api
    #[On('subject-create')]
    public function generate_autor($author){

        $this->name = $author['name'] ?? null;
        $this->birthdate = $author['birth_date'] ?? null;
        $this->description = $author['bio'] ?? null;
        $this->cover_image_url = $author['picture'] ?? null;
        
        \Flux\Flux::modal('create-subject')->show();
    }

    // funcion para crear item
    public function save(){
        // datos automaticos
        $this->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $this->slug = \Illuminate\Support\Str::slug($this->name . '-' . \Illuminate\Support\Str::random(4));
        $this->uuid = \Illuminate\Support\Str::random(24);

        // validacion
        $validated_data = $this->validate();

        // crear dato
        Subject::create($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('create-subject')->close();

        // mensaje de success
        session()->flash('success', 'Creado correctamente');

        // redireccionar
        $this->redirectRoute('subjects', navigate:true);
    }
    
    // render de pagina
    public function render()
    {
        return view('livewire.subject.subject-create');
    }
}
