<div>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Edite diario</flux:heading>
                <flux:text class="mt-2">modifique nota del dia.</flux:text>
            </div>

            <flux:input wire:model='day' label="Dia" type="date" />

            <flux:select wire:model="humor" placeholder="Seleccionar...">
                @foreach ($humor_status as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model='title' label="Titulo" placeholder="Titulo del dia" />
            
            {{-- <flux:textarea wire:model='content' label="Contenido" placeholder="Coloque el contenido" resize="vertical"/> --}}

            <x-pages.forms.quill-textarea-form 
                id_quill="editor_edit_content" 
                name="content"
                rows="15" 
                placeholder="{{ __('Notas personal') }}" model="content"
                model_data="{{ $content }}" 
            />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='update' type="submit" variant="primary">Actualizar</flux:button>
            </div>
        </div>
</div>