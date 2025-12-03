<?php

namespace App\Livewire\Diary;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Diary\Diary;
use Livewire\WithFileUploads;
use App\Models\Diary\DiaryImage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class DiaryCreate extends Component
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

    public $images = []; // hasta 6

    // reglas de validacion
    protected function rules(){
        return [
            'day' => ['required', 'date'],
            'humor' => ['required', 'numeric'],
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
        $diary = Diary::create($validated_data);

        foreach ($this->images as $upload) {
            $filename = uniqid() . '.jpg';
            $path_folder = "diary/{$diary->id}/";
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
                'diary_id' => $diary->id,
            ]);
        }

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
