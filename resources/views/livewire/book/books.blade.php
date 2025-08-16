<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    {{-- boton para abrir modal y crear --}}
    <flux:modal.trigger name="create-book">
        <flux:button size="sm" variant="primary" color="purple" icon="plus">Nuevo</flux:button>
    </flux:modal.trigger>

    
    <flux:icon.loading class="block w-full" wire:loading.delay />

    {{-- modales de crear y editar --}}
    <livewire:book.book-create>
    <livewire:subject.subject-create>
    <livewire:book.book-edit>

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
                            <div class=" flex items-center justify-center gap-1">
                                {{-- <span class="flex w-3 h-3 me-3 bg-gray-500 rounded-full"></span> --}}
                                <span class="text-sm italic">{{ $status_book[$item->status] ?? 'Desconocido' }}</span>
                            </div>
                            <div class="flex items-center justify-center gap-1">
                                    <flux:button variant="ghost" color="blue" icon="pencil-square" size="sm" wire:click="edit('{{ $item->uuid }}')"></flux:button>
                                    <flux:button variant="ghost" color="red" icon="trash" size="sm" wire:click="delete('{{ $item->uuid }}')"></flux:button>
                            </div>
                        </div>
                    </div>
                </section>
                @endforeach
            </div>
            
            {{-- Paginacion --}}
            <div class="mt-2 py-1 px-3">{{ $books->onEachSide(1)->links() }}</div>
            {{-- end Paginacion --}}
        </div>

        {{-- tabla --}}
        {{-- <div class="overflow-x-auto">
            <table class="min-w-full text-sm sm:text-base">
                <thead class="">
                    <tr>
                        @foreach (['cover_image_url' => 'Imagen', 'title' => 'Titulo', 'actions' => 'Acciones'] as $field => $label)
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
                    @forelse ($books as $book)
                        <tr class="hover:bg-gray-50 hover:text-gray-800 border-b border-gray-300 text-center">
                            <td class="px-3 py-2 whitespace-nowrap">
                                <div class="flex gap-1 justify-center items-center">
                                    <img class="max-w-16 h-20 object-cover rounded"
                                        src="{{ $book->cover_image_url ?? 'https://static.vecteezy.com/system/resources/previews/004/141/669/non_2x/no-photo-or-blank-image-icon-loading-images-or-missing-image-mark-image-not-available-or-image-coming-soon-sign-simple-nature-silhouette-in-frame-isolated-illustration-vector.jpg'}}"
                                        alt="imagen">
                                </div>
                            </td>
                            <td class="px-3 py-2 whitespace-nowrap">{{ $book->title }}</td>
                            <td class="px-3 py-2 whitespace-nowrap">
                                <div class="flex gap-1 justify-center items-center">
                                    <flux:button icon="pencil-square" size="sm" wire:click="edit('{{ $book->uuid }}')">Editar</flux:button>
                                    <flux:button icon="trash" size="sm" variant="danger" wire:click="delete('{{ $book->uuid }}')">Borrar</flux:button>
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
        </div> --}}

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
