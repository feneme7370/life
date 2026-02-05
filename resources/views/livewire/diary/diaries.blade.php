<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['singular']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['singular'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- boton para abrir modal y crear --}}
    <div class="flex justify-between items-center gap-1">
        <a href="{{ route('diary_create') }}" >
            <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Nuevo</flux:button>
        </a>

        <flux:button wire:click='clearDate' size="sm" variant="ghost" color="purple" icon="calendar" size="sm">Limpiar</flux:button>

    </div>


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

<div wire:ignore class="text-center my-1">
  <input id="diary_calendar" type="text" hidden readonly />
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>
<script>
    document.addEventListener('livewire:navigated', () => {

        const input = document.getElementById('diary_calendar');
        if (!input) return; // si el input no existe todavía, no inicializamos

        if (window.myPicker) {
            window.myPicker.destroy();
        }

            window.myPicker = new Litepicker({
            element: document.getElementById('diary_calendar'),
            format: 'YYYY-MM-DD',
            singleMode: true,
            inlineMode: true,
            highlightedDays: @json($highlightedDays),
            setup: (picker) => {
                picker.on('selected', (date) => {
                    // Cuando seleccionás un día, lo mandamos a Livewire
                    Livewire.dispatch('reading-day-selected', { date: date.format('YYYY-MM-DD') });
                });
            }
        });
    });
</script>
@endpush

<style>
    .litepicker .day-item.is-highlighted
    /* .litepicker .day-item.is-selected  */
    {
        background-color: #70157e !important; /* azul Tailwind-600 */
        color: #fff !important;
        border-radius: 40% !important; /* redondeado */
    }

    .litepicker .day-item.is-inRange,
    .litepicker .day-item.is-start-date,
    .litepicker .day-item.is-end-date
    {
        background-color: #40024a !important; /* azul Tailwind-600 */
        color: #fff !important;
        border-radius: 30% !important; /* redondeado */
    }


</style>

    <div class="p-4">
        {{-- buscar item --}}

        <div class="flex justify-between items-center gap-1">
            {{-- buscar item --}}
            <input type="text" wire:model.debounce.500ms.live="search"
                placeholder="Buscar..."
                class="border-b px-3 py-1 mb-3 w-8/12">

            <flux:dropdown class="px-3 py-1 mb-3 w-3/12 ">
                <flux:button class="text-xs text-center" icon:trailing="chevron-down">Ver</flux:button>

                <flux:menu>
                    <flux:menu.radio.group wire:model.debounce.500ms.live="perPage">
                        <flux:menu.radio>500</flux:menu.radio>
                        <flux:menu.radio>10000</flux:menu.radio>
                    </flux:menu.radio.group>
                </flux:menu>
            </flux:dropdown>

        </div>

        <flux:button class="text-xs text-center" wire:click="export()">Excel</flux:button>

        {{-- tabla --}}
        <div class="">
            @foreach ($diaries as $diary)
                <div class="my-1 rounded-lg shadow-md p-3 overflow-hidden">
                    <a href="{{ route('diary_view', ['uuid' => $diary->uuid]) }}"><p class="flex items-center gap-2 text-sm mb-1">{{ $diary->day }} {{ $humor_status[$diary->humor] ?? 'Desconocido' }}</p></a>



                    <p class="line-clamp-1 text-sm mb-1">{{ $diary->title }}</p>
                    <span class="line-clamp-3 text-xs">{!! $diary->content !!}</span>
                    
                        <div class="flex gap-1 justify-end items-center">
                            <flux:button variant="ghost" color="blue" icon="document-duplicate" size="sm" wire:click="duplicate('{{ $diary->id}}')"></flux:button>
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
