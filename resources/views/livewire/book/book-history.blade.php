<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Ultimos {{ $title['plural'] }} leidos</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    <div class="p-4">
        <div class="grid grid-cols-12 gap-1">
            {{-- buscar item --}}
            <input type="text" wire:model.debounce.500ms.live="search"
                placeholder="Buscar..."
                class="border-b px-3 py-1 mb-3 w-full col-span-4">

            <span class="col-span-7"></span>

            <flux:dropdown>
                <flux:button class="col-span-1 text-center" icon:trailing="chevron-down">Ver</flux:button>

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

        {{-- listado --}}
        <flux:separator text="Ultimos leidos ({{ $reads->count() }})" />
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

        {{-- listado --}}
        <flux:separator text="Pendientes de leer" />
        {{-- <p class="italic bont-bold text-lg dark:text-gray-50">Pendientes de leer</p> --}}
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <div class="grid grid-cols-1 gap-1 p-1">
                <!-- Aquí repetir el card anterior para cada libro -->
                
                @foreach ($books_to_reads as $books_to_read)
                    @if (!$books_to_read->reads->count())
                        <a 
                            class="hover:underline" 
                            href="{{ route('book_view', ['uuid' => $books_to_read->uuid]) }}"
                        >
                                <span class="font-bold">{{ $books_to_read->title }}</span>
                                - 
                                @foreach ($books_to_read->book_subjects as $item)
                                    {{ $item->name }}, 
                                @endforeach

                        </a>
                    @endif
                @endforeach
                
            </div>
            
            {{-- Paginacion --}}
            <div class="mt-2 py-1 px-3">{{ $books_to_reads->onEachSide(1)->links() }}</div>
            {{-- end Paginacion --}}
        </div>

        {{-- listado --}}
        <flux:separator text="Pendientes de comentar" />
        {{-- <p class="italic bont-bold text-lg dark:text-gray-50">Pendientes de comentar</p> --}}
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <div class="grid grid-cols-1 gap-1 p-1">
                <!-- Aquí repetir el card anterior para cada libro -->
                
                @foreach ($books_to_comments as $books_to_comment)
                    @if (!$books_to_comment->notes)
                        <a 
                            class="hover:underline" 
                            href="{{ route('book_view', ['uuid' => $books_to_comment->uuid]) }}"
                        >
                                <span class="font-bold">{{ $books_to_comment->title }}</span>
                                - 
                                @foreach ($books_to_comment->book_subjects as $item)
                                    {{ $item->name }}, 
                                @endforeach

                        </a>
                    @endif
                @endforeach
                
            </div>
            
            {{-- Paginacion --}}
            <div class="mt-2 py-1 px-3">{{ $books_to_reads->onEachSide(1)->links() }}</div>
            {{-- end Paginacion --}}
        </div>

    </div>

    <a href="{{ route('book_list_history') }}">
        <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Listado completo para copiar</flux:button>
    </a>
</div>
