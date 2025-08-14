<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- boton para abrir modal y crear --}}
    <flux:modal.trigger name="create-tag">
        <flux:button >Nuevo</flux:button>
    </flux:modal.trigger>

    {{-- modales de crear y editar --}}
    <livewire:tag.tag-create>
    <livewire:tag.tag-edit>

    {{-- mensaje de success --}}
    @session('success')
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false }, 3000)"
            class="fixed top-5 right-5 bg-green-600 text-gray-50 text-sm p-4 rounded-lg shadow"
            role="alert"
        >
            <p>{{ $value }}</p>
        </div>
    @endsession

    <div class="p-4">
        {{-- buscar item --}}
        <input type="text" wire:model.debounce.500ms.live="search"
            placeholder="Buscar..."
            class="border-b px-3 py-1 mb-3 w-full sm:w-1/3">

        {{-- tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm sm:text-base">
                <thead class="">
                    <tr>
                        @foreach (['cover_image_url' => 'Imagen', 'name' => 'Nombre', 'actions' => 'Acciones'] as $field => $label)
                            <th class="px-3 py-2 border-b border-gray-300 cursor-pointer font-medium whitespace-nowrap"
                                wire:click="sortBy('{{ $field }}')">
                                {{ $label }}
                                @if ($sortField === $field)
                                    @if ($sortDirection === 'asc')
                                        ↑
                                    @else
                                        ↓
                                    @endif
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tags as $tag)
                        <tr class="hover:bg-gray-50 hover:text-gray-800 border-b border-gray-300 text-center">
                            <td class="px-3 py-2 whitespace-nowrap">
                                <div class="flex gap-1 justify-center items-center">
                                    <img class="w-16 h-16 object-cover rounded"
                                        src="{{ $tag->cover_image_url ?? 'https://static.vecteezy.com/system/resources/previews/004/141/669/non_2x/no-photo-or-blank-image-icon-loading-images-or-missing-image-mark-image-not-available-or-image-coming-soon-sign-simple-nature-silhouette-in-frame-isolated-illustration-vector.jpg'}}"
                                        alt="imagen">
                                </div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $tag->name }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">
                                <div class="flex gap-1 justify-center items-center">
                                    <flux:button icon="pencil-square" size="sm" wire:click="edit('{{ $tag->uuid }}')">Editar</flux:button>
                                    <flux:button icon="trash" size="sm" variant="danger" wire:click="delete('{{ $tag->uuid }}')">Borrar</flux:button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-3 py-2 text-center">No hay resultados</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- paginacion --}}
        <div class="mt-3">
            {{ $tags->links() }}
        </div>
    </div>

    {{-- modal para eliminar item --}}
    <flux:modal name="delete-tag" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar dato?</flux:heading>

                <flux:text class="mt-2">
                    <p>Quiere eliminar {{ $title['singular'] }}?.</p>
                    <p>Esta accion no puede revertirse.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click='destroy()' type="submit" variant="danger">Borrar</flux:button>
            </div>
        </div>
    </flux:modal>

</div>
