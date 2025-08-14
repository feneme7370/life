<?php

namespace App\Livewire\Subject;

use App\Models\Subject;
use Livewire\Component;
use Livewire\Attributes\On;

class SubjectEdit extends Component
{
    // propiedades del item
    public $name, $birthdate, $country, $description, $cover_image_url, $slug, $uuid, $user_id;
    public $subjectId, $subject;

    // reglas de validacion
    protected function rules(){
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('subjects', 'slug')->ignore($this->subject?->id ?? 0)],
            'birthdate' => ['nullable', 'date'],
            'country' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'cover_image_url' => ['nullable', 'url', 'max:65535'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('subjects', 'uuid')->ignore($this->subject?->id)],
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

    // recibe llamada al componente y activa la funcion edit
    #[On('subject-edit')]
    public function edit($uuid){
        $this->subject = Subject::where('uuid', $uuid)->first();

        $this->name = $this->subject->name; 
        $this->birthdate = $this->subject->birthdate; 
        $this->country = $this->subject->country; 
        $this->description = $this->subject->description; 
        $this->cover_image_url = $this->subject->cover_image_url; 
        $this->user_id = $this->subject->user_id; 
        $this->uuid = $this->subject->uuid; 

        \Flux\Flux::modal('edit-subject')->show();
    }

    // funcion para editar item
    public function update(){
        // datos automaticos
        $this->slug = \Illuminate\Support\Str::slug($this->name . '-' . \Illuminate\Support\Str::random(4));

        // validacion
        $validated_data = $this->validate();

        // actualizar dato
        $this->subject->update($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('edit-subject')->close();

        // mensaje de success
        session()->flash('success', 'Editado correctamente');

        // redireccionar
        $this->redirectRoute('subjects', navigate:true);
    }

    // render de pagina
    public function render()
    {
        return view('livewire.subject.subject-edit');
    }
}
