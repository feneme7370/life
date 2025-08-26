<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['singular']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['singular'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- boton para abrir modal y crear --}}
        <a href="{{ route('diary_create') }}">
            <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Nuevo</flux:button>
        </a>

    {{-- modales de crear y editar --}}
    {{-- <livewire:diary.diary-create>
    <livewire:diary.diary-edit> --}}

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
        <div class="">
            @foreach ($diaries as $diary)
                <div class="my-1 rounded-lg shadow-md p-3 overflow-hidden">
                    <a href="{{ route('diary_view', ['uuid' => $diary->uuid]) }}"><p class="flex items-center gap-2 text-sm mb-1">{{ $diary->day }} {{ $humor_status[$diary->humor] ?? 'Desconocido' }}</p></a>



                    <p class="line-clamp-1 text-sm mb-1">{{ $diary->title }}</p>
                    <span class="line-clamp-3 text-xs">{!! $diary->content !!}</span>
                    
                        <div class="flex gap-1 justify-end items-center">
                            <a href="{{ route('diary_edit', ['uuid' => $diary->uuid]) }}">
                                <flux:button variant="ghost" color="blue" icon="pencil-square" size="sm"></flux:button>
                            </a>
                            <flux:button variant="ghost" color="red" icon="trash" size="sm" wire:click="delete('{{ $diary->uuid }}')"></flux:button>
                        </div>
                </div>
            @endforeach
        </div>

        {{-- paginacion --}}
        <div class="mt-3">
            {{ $diaries->links() }}
        </div>
    </div>

    <a href="{{ route('diary_list_history') }}">
        <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Listado completo para copiar</flux:button>
    </a>

    {{-- modal para eliminar item --}}
    <flux:modal name="delete-diary" class="min-w-[22rem]">
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
