<?php

namespace App\Livewire\Diary;

use App\Models\Diary\Diary;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\Attributes\On;

class DiaryCreate extends Component
{
    // propiedades del item
    public 
    $day,
    $humor,
    $title, 
    $content, 
    $uuid, 
    $user_id;

    // reglas de validacion
    protected function rules(){
        return [
            'day' => ['required', 'date'],
            'humor' => ['required', 'numeric'],
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

    public function mount(){
        $this->day = Carbon::now()->format('Y-m-d');
        $this->humor = 1;
    }
    // funcion para crear item
    public function save(){
        // datos automaticos
        $this->user_id = \Illuminate\Support\Facades\Auth::user()->id;
        $this->uuid = \Illuminate\Support\Str::random(24);

        // validacion
        $validated_data = $this->validate();

        // crear dato
        Diary::create($validated_data);

        // resetear propiedades
        $this->reset();

        // cerrar modal
        \Flux\Flux::modal('create-diary')->close();

        // mensaje de success
        session()->flash('success', 'Creado correctamente');

        // redireccionar
        $this->redirectRoute('diaries', navigate:true);
    }
    
    // render de pagina
    public function render()
    {
        $humor_status = Diary::humor_status();
        return view('livewire.diary.diary-create', compact(
            'humor_status'
        ));
    }
}
