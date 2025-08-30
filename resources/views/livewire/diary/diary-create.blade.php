<div>
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Crear diario</flux:heading>
                <flux:text class="mt-2">Agregar nota del dia.</flux:text>
            </div>

            <flux:input wire:model='day' label="Dia" type="date"/>

            <flux:select wire:model="humor" placeholder="Seleccionar...">
                @foreach ($humor_status as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </flux:select>

            <flux:input wire:model='title' label="Titulo" placeholder="Titulo del dia" />

            <x-pages.forms.quill-textarea-form 
                id_quill="editor_create_content" 
                name="content"
                rows="15" 
                placeholder="{{ __('Notas personal') }}" model="content"
                model_data="{{ $content }}" 
            />

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' type="submit" variant="primary">Guardar</flux:button>
            </div>
        </div>
</div>
