<div>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Editar Libro</flux:heading>
                <flux:text class="mt-2">Modificar libro.</flux:text>
            </div>

            <flux:icon.loading class="block w-full" wire:loading.delay />

            <flux:text class="text-xs italic">Datos del libro</flux:text>
            
            <flux:input wire:model='title' label="Titulo" placeholder="Titulo del libro" />
            <flux:input wire:model='original_title' label="Titulo original" placeholder="Titulo original" />
            <flux:textarea wire:model='synopsis' label="Sinopsis" placeholder="Coloque una sinopsis" resize="vertical"/>
            
            <div class="grid gap-1 grid-cols-3">
                <flux:input wire:model='release_date' type="number" min="1" max="2999" label="Publicacion" />
                <flux:input wire:model='pages' label="Paginas" placeholder="Cantidad" />
                <flux:select wire:model="category" label="Categoria" placeholder="Seleccionar...">
                    @foreach ($category_book as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>
            </div>
            
            <flux:input wire:model='cover_image_url' label="Link de Imagen" placeholder="Coloque el enlace de la imagen" />

            <div class="grid gap-1 grid-cols-3">
                <flux:select wire:model="media_type" placeholder="Seleccionar...">
                    @foreach ($media_type_content as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="format" placeholder="Seleccionar...">
                    @foreach ($format_book as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>
                <flux:select wire:model="language" placeholder="Seleccionar...">
                    @foreach ($language_book as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>
            </div>
            <flux:text class="text-xs italic">Asociaciones del libro</flux:text>

            <div class="col-span-12 sm:col-span-6">
                <x-pages.forms.select-multiple
                    model="subject" 
                    relation="book_subject" 
                    wire:model="selected_book_subjects" 
                    label="Sujetos"
                    :items="$subjects"
                    wire_model="author_name"
                />
            </div>

            <div class="col-span-12 sm:col-span-6">
                <flux:input class="col-span-8"  wire:model='number_collection' label="Numero de coleccion" placeholder="Numero" />

                <x-pages.forms.select-multiple
                    model="collection" 
                    relation="book_collection" 
                    wire:model="selected_book_collections" 
                    label="Coleccion"
                    :items="$collections"
                    wire_model="collections_name"
                />
            </div>

            <div class="col-span-12 sm:col-span-6">
                <x-pages.forms.select-multiple
                    model="tag" 
                    relation="book_tag" 
                    wire:model="selected_book_tags" 
                    label="Etiquetas"
                    :items="$tags"
                    wire_model="tags_name"
                />
            </div>

            <div class="col-span-12 sm:col-span-6">
                <x-pages.forms.select-multiple
                    model="genre" 
                    relation="book_genre" 
                    wire:model="selected_book_genres" 
                    label="Generos"
                    :items="$genres"
                    wire_model="genres_name"
                />
            </div>

            <div class="flex gap-2 items-center">

                <flux:button wire:click="modalRead" class="mt-1" size="sm" variant="ghost" color="purple" icon="plus" type="submit"></flux:button>
                <flux:separator text="Lecturas" />
                
            </div>

            @if ($book->reads)
                @foreach ($book->reads as $read)
                <div class="flex items-start justify-between">
                    <div class="px-3 border-l-4 border-purple-800">
                        @if ($read->end_read)
                            <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $read->start_read }} - {{ $read->end_read }} en {{ \Carbon\Carbon::parse($read->start_read)->diffInDays($read->end_read) }} dias</p>
                        @else
                            <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $read->start_read }} - {{ $read->end_read }} Leyendo</p>
                        @endif

                    </div>

                    <flux:button wire:click="deleteRead({{ $read->id }})" class="ml-3 text-gray-400 hover:text-red-500 transition" size="sm" variant="ghost" color="purple" type="submit">✕</flux:button>
                </div>
                @endforeach
            @endif

            <flux:text class="text-xs italic">Personal</flux:text>

            {{-- <flux:textarea wire:model='summary' label="Resumen personal" placeholder="Coloque un resumen" resize="vertical"/> --}}
            {{-- <flux:textarea wire:model='notes' label="Notas" placeholder="Coloque las notas" resize="vertical"/> --}}

            <x-pages.forms.quill-textarea-form 
                id_quill="editor_create_summary" 
                name="summary"
                rows="15" 
                placeholder="{{ __('Resumen personal') }}" model="summary"
                model_data="{{ $summary }}" 
            />
            
            <x-pages.forms.quill-textarea-form 
                id_quill="editor_create_notes" 
                name="notes"
                rows="15" 
                placeholder="{{ __('Reseña') }}" model="notes"
                model_data="{{ $notes }}" 
            />

            <div class="grid gap-3 grid-cols-2">
    
                <flux:select wire:model="rating" placeholder="Seleccionar...">
                    @foreach ($rating_stars as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </flux:select>

                <flux:field variant="inline" class="flex items-center">
                    <flux:checkbox wire:model="is_favorite" />

                    <flux:label>Favorito</flux:label>

                    <flux:error name="is_favorite" />
                </flux:field>

                <flux:field variant="inline" class="flex items-center">
                    <flux:checkbox wire:model="status" />

                    <flux:label>Abandonado?</flux:label>

                    <flux:error name="status" />
                </flux:field>

            </div>

            <div class="flex">
                <flux:spacer />

                <flux:button size="sm" variant="primary" color="purple" icon="plus" wire:click='update' type="submit">Actualizar</flux:button>
            </div>
        </div>


    {{-- modales para agrear y eliminar lecturas --}}
    <flux:modal name="add-read" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Lectura</flux:heading>
                <flux:text class="mt-2">Agregue una fecha de lectura.</flux:text>
            </div>

            {{-- <flux:textarea wire:model='quoteContent' row="20" label="Cita o Frase" placeholder="Coloque la la cita o frase" resize="vertical"/> --}}
            <div class="grid grid-cols-2 gap-1">
                <flux:input wire:model='start_read' type="date" max="2999-12-31" label="Inicio de lectura" />
                <flux:input wire:model='end_read' type="date" max="2999-12-31" label="Fin de lectura" />
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click="addRead" type="submit" variant="primary">Agregar</flux:button>
            </div>
        </div>
    </flux:modal>


    <flux:modal name="delete-read" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar</flux:heading>

                <flux:text class="mt-2">
                    <p>Desea eliminar esta lectura?.</p>
                    <p>Esta accion no puede revertirse.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click="destroyRead" type="submit" variant="danger">Borrar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
