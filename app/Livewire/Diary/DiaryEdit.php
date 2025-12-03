<?php

namespace App\Livewire\Diary;

use Livewire\Component;
use App\Models\Diary\Diary;
use Livewire\WithFileUploads;
use App\Models\Diary\DiaryImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DiaryEdit extends Component
{
    use WithFileUploads;
    
    // propiedades del item
    public 
    $day,
    $humor,
    $title, 
    $content, 
    $uuid, 
    $user_id;
    public $diaryId, $diary;

    public $images = []; // hasta 6

    // reglas de validacion
    protected function rules(){
        return [
            'day' => ['required', 'date'],
            'humor' => ['required', 'string', 'max:255'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'uuid' => ['required', 'string', 'max:255', \Illuminate\Validation\Rule::unique('diaries', 'uuid')->ignore($this->diary?->id ?? 0)],
            'user_id' => ['required', 'exists:users,id'],

            'images.*' => 'nullable|image|max:2048', // 2MB c/u
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

        'images' => 'imagenes',
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

        foreach ($this->images as $upload) {
            $filename = uniqid() . '.jpg';
            $path_folder = "diary/{$this->diary->id}/";
            $path = $path_folder . $filename;
            
            // Verificar si la carpeta existe, si no, crearla
            if (!file_exists($path_folder)) {
                mkdir($path_folder, 0755, true);
            }

            // create new manager instance with desired driver
            $manager = new ImageManager(Driver::class);

            // read jpeg image
            $image = $manager->read($upload);

            // editar imagenes
            $image = $image->scale(width:800);

            $image->save($path);
            
            DiaryImage::create([
                'path' => $path,
                'diary_id' => $this->diary->id,
            ]);
        }

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
