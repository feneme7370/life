<div>
    <flux:modal name="edit-genre" class="w-full">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Crear Genero</flux:heading>
                <flux:text class="mt-2">Agregar genero.</flux:text>
            </div>

            <flux:input wire:model='name' label="Titulo" placeholder="Titulo del genero" />

            <flux:input wire:model='cover_image_url' label="Link de Imagen" placeholder="Coloque el enlace de la imagen" />

            <flux:textarea wire:model='description' label="Descripcion" placeholder="Coloque una descripcion del genero" resize="vertical"/>

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='update' type="submit" variant="primary">Actualizar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>

