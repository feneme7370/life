<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- boton para abrir modal y crear --}}
    {{-- <flux:modal.trigger name="create-book"> --}}
        <a href="{{ route('book_create') }}">
            <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Nuevo</flux:button>
        </a>
    {{-- </flux:modal.trigger> --}}

    
    <flux:icon.loading class="block w-full" wire:loading.delay />

    {{-- <livewire:subject.subject-create> --}}

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

        {{-- cuadricula --}}
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <div class="w-full grid gap-1 grid-col-12 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 2xl:grid-cols-5">
                @foreach ($books as $item)
                <section
                    class="w-full p-2 border border-purple-200  dark:bg-gray-900 dark:border-gray-900 rounded-md mt-1 flex flex-col justify-between items-start">

                    <div class="grid grid-cols-12 gap-3 w-full">

                        <img class="h-auto rounded-md object-cover col-span-4" src="{{ $item->cover_image_url }}"
                            alt="Portada">

                        <div class="w-full col-span-8">

                            <div class="text-sm md:text-base font-semibold hover:underline line-clamp-3">
                                <a
                                    href="{{ route('book_view', ['uuid' => $item->uuid]) }}"
                                >{{ $item->title }}</a>
                            </div>

                            <div class="flex flex-col gap-1">
                                <span class="text-sm md:font-normal text-gray-400">{{ $item->media_type == null ? '' :
                                    $media_type_content[$item->media_type] }} ({{ \Carbon\Carbon::parse($item->release_date)->year
                                    }})</span>
                                <span class="text-sm md:font-normal text-gray-400">{{ $rating_stars[$item->rating] ??
                                    'Sin valorar' }}</span>
                                <span class="text-sm md:font-normal text-gray-400">{{ $item->pages }} Pags.</span>
                            </div>

                            <div class="flex gap-1">
                                @foreach ($item->book_subjects as $item_subject)
                                <a class="text-xs hover:underline text-gray-400"
                                    href="{{ route('books_library', ['a' => $item_subject->uuid]) }}"
                                >
                                    {{ $item_subject->name}}
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1 mt-1">
                        <div class="flex gap-1 flex-wrap">
                            @foreach ($item->book_collections as $collection_item)
                                <flux:badge size="sm" variant="pill" as="button" variant="solid" color="purple">
                                    <a 
                                        href="{{ route('books_library', ['c' => $collection_item->uuid]) }}"
                                    >
                                        {{$collection_item->name }}
                                    </a>
                                </flux:badge>
                            @endforeach
                        </div>
                        <div class="flex gap-1 flex-wrap">
                            @foreach ($item->book_genres as $genre_item)
                                <flux:badge size="sm" variant="pill" as="button" variant="" color="purple">
                                    <a 
                                        href="{{ route('books_library', ['g' => $genre_item->uuid]) }}"
                                    >
                                        {{$genre_item->name }}
                                    </a>
                                </flux:badge>
                            @endforeach
                        </div>

                        {{-- <div class="flex gap-1 flex-wrap">
                            @foreach ($item->book_tags as $tag_item)
                                <flux:badge size="sm" variant="pill" as="button" variant="" color="purple">
                                    <a 
                                        href="{{ route('books_library', ['c' => $tag_item->uuid]) }}"
                                    >
                                        {{$tag_item->name }}
                                    </a>
                                </flux:badge>
                            @endforeach
                        </div> --}}
                        
                    </div>

                    <div class="w-full">
                        <div class="flex justify-between">
                            <div class=" flex items-start justify-center flex-col gap-1">
                                @if ($item->reads->count())
                                    <span class="text-sm italic">{{ $status_book[$item->status] ?? 'Desconocido' }}</span>
                                @endif
                                @if ($item->notes)
                                    <span class="text-sm italic">✍️ Comentado</span>
                                @endif
                            </div>
                            <div class="flex items-center justify-center gap-1">
                                    
                                <a href="{{ route('book_edit', ['uuid' => $item->uuid]) }}">
                                    <flux:button variant="ghost" color="blue" icon="pencil-square" size="sm"></flux:button>
                                </a>
                                
                                <flux:button variant="ghost" color="red" icon="trash" size="sm" wire:click="delete('{{ $item->uuid }}')"></flux:button>
                            </div>
                        </div>
                    </div>
                </section>
                @endforeach
            </div>

        </div>

        {{-- paginacion --}}
        <div class="mt-3">
            {{ $books->links() }}
        </div>
    </div>

    {{-- modal para eliminar item --}}
    <flux:modal name="delete-book" class="min-w-[22rem]">
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
