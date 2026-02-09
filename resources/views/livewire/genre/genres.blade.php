<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- boton para abrir modal y crear --}}
    <flux:modal.trigger name="create-genre">
        <flux:button >Nuevo</flux:button>
    </flux:modal.trigger>
    <flux:button class="text-xs text-center" wire:click="export('genres')">Excel</flux:button>
        <flux:button class="text-xs text-center" wire:click="exportAsociation('book_genre')">ExcelAsociation</flux:button>

    {{-- modales de crear y editar --}}
    <livewire:genre.genre-create />
    <livewire:genre.genre-edit />

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
        <div class="flex justify-between items-center gap-1">
            {{-- buscar item --}}
            <input type="text" wire:model.debounce.500ms.live="search"
                placeholder="Buscar..."
                class="border-b px-3 py-1 mb-3 w-8/12">

            <flux:dropdown class="px-3 py-1 mb-3 w-3/12 ">
                <flux:button class="text-xs text-center" icon:trailing="chevron-down">Ver</flux:button>

                <flux:menu>
                    <flux:menu.radio.group wire:model.debounce.500ms.live="perPage">
                        <flux:menu.radio checked>30</flux:menu.radio>
                        <flux:menu.radio>50</flux:menu.radio>
                        <flux:menu.radio>100</flux:menu.radio>
                        <flux:menu.radio>500</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

        </div>

        {{-- tabla --}}
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm sm:text-base">
                <thead class="">
                    <tr>
                        @foreach (['name' => 'Nombre', 'actions' => 'Acciones'] as $field => $label)
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
                    @forelse ($genres as $genre)
                        <tr class="hover:bg-gray-50 hover:text-gray-800 border-b border-gray-300 text-center">
                            <td class="px-3 py-1 whitespace-nowrap">{{ $genre->name }}</td>
                            <td class="px-3 py-1 whitespace-nowrap">
                                <div class="flex gap-1 justify-center items-center">
                                    <a wire:click="edit('{{ $genre->uuid }}')">
                                        <flux:button variant="ghost" color="blue" icon="pencil-square" size="sm"></flux:button>
                                    </a>
                                    
                                    <flux:button variant="ghost" color="red" icon="trash" size="sm" wire:click="delete('{{ $genre->uuid }}')"></flux:button>
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
            {{ $genres->links() }}
        </div>
    </div>

    {{-- modal para eliminar item --}}
    <flux:modal name="delete-genre" class="min-w-[22rem]">
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
