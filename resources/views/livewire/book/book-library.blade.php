<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }} leidos</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    <div class="p-1">
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

        {{-- cuadricula --}}
        <div class="relative shadow-md sm:rounded-lg">
            <div class="flex flex-wrap justify-center gap-1 px-1 py-3">
                <!-- Aquí repetir el card anterior para cada libro -->
                
                @foreach ($books as $item)
                <a 
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
            
        </div>

        {{-- paginacion --}}
        <div class="mt-3">
            {{ $books->links() }}
        </div>
    </div>
</div>
