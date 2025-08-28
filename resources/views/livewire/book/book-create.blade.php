<div>
    {{-- <flux:modal name="create-book" class="w-full"> --}}
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Crear Libro</flux:heading>
                <flux:text class="mt-2">Agregar libro.</flux:text>
            </div>

            <flux:icon.loading class="block w-full" wire:loading.delay />

            <flux:text class="text-xs italic">Datos del libro</flux:text>
            
            <flux:input wire:model='title' label="Titulo" placeholder="Titulo del libro" />
            <flux:input wire:model='original_title' label="Titulo original" placeholder="Titulo original" />
            <flux:textarea wire:model='synopsis' label="Sinopsis" placeholder="Coloque una sinopsis" resize="vertical"/>
            
            <div class="grid gap-1 grid-cols-3">
                <flux:input wire:model='release_date' type="date" max="2999-12-31" label="Publicacion" />
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
                />
            </div>

            <div class="col-span-12 sm:col-span-6">

                <flux:input class="col-span-8"  wire:model='number_collection' label="Numero de coleccion" placeholder="Numero" />
                <x-pages.forms.select-multiple
                    class="col-span-8" 
                    model="collection" 
                    relation="book_collection" 
                    wire:model="selected_book_collections" 
                    label="Coleccion"
                    :items="$collections"
                />

            </div>

            <div class="col-span-12 sm:col-span-6">
                <x-pages.forms.select-multiple
                    model="tag" 
                    relation="book_tag" 
                    wire:model="selected_book_tags" 
                    label="Etiquetas"
                    :items="$tags"
                />
            </div>

            <div class="col-span-12 sm:col-span-6">
                <x-pages.forms.select-multiple
                    model="genre" 
                    relation="book_genre" 
                    wire:model="selected_book_genres" 
                    label="Generos"
                    :items="$genres"
                />
            </div>

            <flux:text class="text-xs italic">Personal</flux:text>

            {{-- <flux:textarea wire:model='summary' label="Resumen personal" placeholder="Coloque un resumen" resize="vertical"/>
            <flux:textarea wire:model='notes' label="Notas" placeholder="Coloque las notas" resize="vertical"/> --}}

            
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
                placeholder="{{ __('Notas personal') }}" model="notes"
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

            </div>

            <div class="flex">
                <flux:spacer />

                <flux:button size="sm" variant="primary" color="purple" icon="plus" wire:click='save' type="submit">Guardar</flux:button>
            </div>
        </div>
    {{-- </flux:modal> --}}
</div>
