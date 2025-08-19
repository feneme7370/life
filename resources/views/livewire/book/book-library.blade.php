<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    <div class="p-4">
        {{-- buscar item --}}
        <input type="text" wire:model.debounce.500ms.live="search"
            placeholder="Buscar..."
            class="border-b px-3 py-1 mb-3 w-full sm:w-1/3">

        {{-- cuadricula --}}
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <div class="grid grid-cols-3 md:grid-cols-4 2xl:grid-cols-6 3xl:grid-cols-10 gap-1">
                <!-- Aquí repetir el card anterior para cada libro -->
                
                @foreach ($books as $item)
                <a 
                    class="mx-auto" 
                    href="{{ route('book_view', ['uuid' => $item->uuid]) }}"
                >
                    <div class="relative w-20 h-32 sm:w-40 sm:h-60 rounded-lg overflow-hidden shadow-lg group">
                        <img src="{{ $item->cover_image_url }}" alt="Portada del libro" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                            <span class="text-white text-lg font-semibold px-2 text-center">{{ $item->title }}</span>
                        </div>
                    </div>
                </a>
                
                
                @endforeach
                
            </div>
            
            {{-- Paginacion --}}
            <div class="mt-2 py-1 px-3">{{ $books->onEachSide(1)->links() }}</div>
            {{-- end Paginacion --}}
        </div>

        {{-- paginacion --}}
        <div class="mt-3">
            {{ $books->links() }}
        </div>
    </div>
</div>
