<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Ultimos {{ $title['plural'] }} leidos</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    <div class="p-4">
        {{-- buscar item --}}
        <input type="text" wire:model.debounce.500ms.live="search"
            placeholder="Buscar..."
            class="border-b px-3 py-1 mb-3 w-full sm:w-1/3">

        {{-- listado --}}
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <div class="grid grid-cols-1 gap-1 p-1">
                <!-- Aquí repetir el card anterior para cada libro -->
                
                @foreach ($reads as $read)
                <a 
                    class="hover:underline" 
                    href="{{ route('book_view', ['uuid' => $read->book->uuid]) }}"
                >
                        <span class="font-bold">{{ $read->book->title }}</span>
                        - 
                        @foreach ($read->book->book_subjects as $item)
                            {{ $item->name }}, 
                        @endforeach

                    <span> ({{ $read->end_read }})</span>
                </a>
                
        
                @endforeach
                
            </div>
            
            {{-- Paginacion --}}
            <div class="mt-2 py-1 px-3">{{ $reads->onEachSide(1)->links() }}</div>
            {{-- end Paginacion --}}
        </div>

    </div>
</div>
