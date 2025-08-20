<?php

namespace App\Livewire\Diary;

use App\Models\Diary\Diary;
use Livewire\Component;
use Livewire\Attributes\On;

class DiaryEdit extends Component
{
    // propiedades del item
    public 
    $day,
    $humor,
    $title, 
    $content, 
    $uuid, 
    $user_id;
    public $diaryId, $diary;

    // reglas de validacion
    protected function rules(){
        return [
            'day' => ['required', 'date'],
            'humor' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('diaries', 'uuid')->ignore($this->diary?->id ?? 0)],
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    // renombrar variables a castellano
    protected $validationAttributes = [
        'day' => 'dia',
        'humor' => 'humor',
        'title' => 'titulo',
        'content' => 'contenido',
        'uuid' => 'uuid',
        'user_id' => 'usuario',
    ];

    // recibe llamada al componente y activa la funcion edit
    public function mount($uuid){
        $this->diary = Diary::where('uuid', $uuid)->first();

        $this->day = $this->diary->day; 
        $this->humor = $this->diary->humor; 
        $this->title = $this->diary->title; 
        $this->content = $this->diary->content; 
        $this->user_id = $this->diary->user_id; 
        $this->uuid = $this->diary->uuid; 

        \Flux\Flux::modal('edit-diary')->show();
    }

    // funcion para editar item
    public function update(){
        // validacion
        $validated_data = $this->validate();

        // actualizar dato
        $this->diary->update($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('edit-diary')->close();

        // mensaje de success
        session()->flash('success', 'Editado correctamente');

        // redireccionar
        $this->redirectRoute('diaries', navigate:true);
    }

    // render de pagina
    public function render()
    {
        $humor_status = Diary::humor_status();
        return view('livewire.diary.diary-edit', compact(
            'humor_status',
        ));
    }
}
