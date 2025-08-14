<div>
    <flux:modal name="create-subject" class="w-full">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Crear Sujeto</flux:heading>
                <flux:text class="mt-2">Agregar sujeto.</flux:text>
            </div>

            <flux:input wire:model='name' label="Nombre y Apellido" placeholder="Su nombre y su apellido" />
            
            <flux:input wire:model='birthdate' label="Fecha de nacimiento" type="date" />
            <flux:input wire:model='country' label="Pais" placeholder="Pais de origen" />

            <flux:input wire:model='cover_image_url' label="Link de Imagen" placeholder="Coloque el enlace de la imagen" />

            <flux:textarea wire:model='description' label="Descripcion" placeholder="Coloque una descripcion del sujeto" resize="vertical"/>

            <div class="flex">
                <flux:spacer />

                <flux:button wire:click='save' type="submit" variant="primary">Guardar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
