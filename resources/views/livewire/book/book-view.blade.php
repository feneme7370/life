<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['singular']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Ver {{ $title['singular'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />
    
    {{-- <livewire:book.book-edit :summary="$book->summary" :notes="$book->notes"> --}}

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

    <div class="w-full ">


        <div class="sm:m-3 p-4 bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:border-gray-900 rounded-lg shadow-sm sm:p-8">

        <div class="grid grid-cols-1 gap-1">
            <img src="{{ $book->cover_image_url }}" class="w-full sm:w-auto sm:h-96 mx-auto mb-1 sm:mb-5" alt="">
    
            <div>
                <div class="flex justify-between items-center gap-1">
                    <div class="mt-4 sm:mt-0 mb-4 ">
                        <h5 class="text-xl sm:text-lg font-bold text-gray-950 dark:text-gray-200 ">{{ $book->title }}</h5>
                        <p class="mb-2 text-xs sm:text-sm text-gray-800 dark:text-gray-300  font-light italic">{{ $book->original_title }}</p>
                    </div>

                        <a href="{{ route('book_edit', ['uuid' => $book->uuid]) }}">
                            <flux:button variant="ghost" color="blue" icon="pencil-square" size="sm"></flux:button>
                        </a>

                </div>
                
                <flux:separator text="Datos" />
        
                <p class="mt-1 mb-3 text-base sm:text-lg text-gray-800 dark:text-gray-300">
                    @foreach ($book->book_subjects as $item)
                        - 
                        <a 
                            class="italic hover:underline  " 
                            href="{{ route('books_library', ['a' => $item->uuid]) }}"
                        >{{ $item->name }}</a>
                    @endforeach
                    <span class="text-gray-950 dark:text-gray-400"> ( {{ \Carbon\Carbon::parse($book->release_date)->year }} )</span>
                </p>
        
                <div class="flex gap-1 items-center text-center">
                    
                        <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                            <span class="text-gray-800 dark:text-gray-300  font-bold">{{$book->number_collection}}</span>
                                ° Volumen 
                        </p>

                        <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                            | 
                            <span class="text-gray-800 dark:text-gray-300  font-bold">{{$book->pages ?? 1}}</span>
                                Pags.
                        </p>

                        <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                            | 
                            <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $media_type_content[$book->media_type] ?? 'Desconocido' }}</span>
                        </p>

                    </div>
                    <div class="flex gap-1 items-center text-center">
                        <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                            | 
                            <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $format_book[$book->format] ?? 'Desconocido' }}</span>
                        </p>

                        <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                            | 
                            <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $category_book[$book->category] ?? 'Desconocido' }}</span>
                        </p>

                        <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                            | 
                            <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $language_book[$book->language] ?? 'Desconocido' }}</span>
                        </p>

                    
                    </div>

                    <p class="mb-4 text-sm sm:text-base text-gray-800 dark:text-gray-300  whitespace-pre-wrap">{{ $book->synopsis }}</p>
            </div>
        </div>



            <flux:separator text="Asociaciones" />

            @if (!$book->book_collections->isEmpty())
            <p class="mt-1 text-sm sm:text-base text-gray-800"><span class="text-gray-950 dark:text-gray-300  font-bold">Coleccion:</span></p>
            <div class="mb-2">
                @foreach ($book->book_collections as $item)
                    <flux:badge size="sm" variant="pill" as="button" variant="solid" color="purple">
                        <a 
                            href="{{ route('books_library', ['c' => $item->uuid]) }}"
                        >{{ $item->name }}</a>
                    </flux:badge>
                @endforeach
            </div>
            @endif

            @if (!$book->book_genres->isEmpty())
            <p class="mt-1 text-sm sm:text-base text-gray-800"><span class="text-gray-950 dark:text-gray-300  font-bold">Generos:</span></p>
            <div class="mb-2">
                @foreach ($book->book_genres as $item)
                    <flux:badge size="sm" variant="pill" as="button" variant="solid" color="purple">
                        <a 
                            href="{{ route('books_library', ['g' => $item->uuid]) }}"
                        >{{ $item->name }}</a>
                    </flux:badge>
                @endforeach
            </div>
            @endif

            @if (!$book->book_tags->isEmpty())
            <p class="mt-1 text-sm sm:text-base text-gray-800"><span class="text-gray-950 dark:text-gray-300  font-bold">Etiquetas:</span></p>
            <div class="mb-2">
                @foreach ($book->book_tags as $item)
                    <flux:badge size="sm" variant="pill" as="button" variant="solid" color="purple">
                        <a 
                            href="{{ route('books_library', ['t' => $item->uuid]) }}"
                        >{{ $item->name }}</a>
                    </flux:badge>
                @endforeach
            </div>
            @endif

            <div class="flex gap-2 items-center">

                {{-- <flux:button wire:click="modalRead" class="mt-1" size="sm" variant="ghost" color="purple" icon="plus" type="submit"></flux:button> --}}
                <flux:separator text="Lecturas" />
                
            </div>

            @if ($book->reads)
                @foreach ($book->reads as $read)
                <div class="flex items-start justify-between">
                    <div class="px-3 border-l-4 border-purple-800">
                        @if ($read->end_read)
                            <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $read->start_read }} - {{ $read->end_read }} en {{ \Carbon\Carbon::parse($read->start_read)->diffInDays($read->end_read) }} dias</p>
                        @else
                            <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $read->start_read }} - {{ $read->end_read }} Leyendo</p>
                        @endif

                    </div>

                    {{-- <flux:button wire:click="deleteRead({{ $read->id }})" class="ml-3 text-gray-400 hover:text-red-500 transition" size="sm" variant="ghost" color="purple" type="submit">✕</flux:button> --}}
                </div>
                @endforeach
            @endif

            <flux:separator text="Anotaciones Personales" />

            <div  class="flex gap-2 items-center">
                {{-- @if ($book->status)
                    <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                        <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $status_book[$book->status] ?? 'Desconocido' }}</span>
                    </p>
                @endif --}}

                @if ($book->rating)
                    <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                        <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $rating_stars[$book->rating] ?? 'Desconocido' }}</span>
                    </p>
                @endif
        
                @if ($book->is_favorite)
                    <p class="mb-2 text-sm sm:text-base text-gray-950 dark:text-gray-400 ">
                        <span class="text-gray-800 dark:text-gray-300  font-bold">{{ $book->is_favorite ? 'Favorito' : '' }}</span>
                        
                    </p>
                @endif
            </div>
{{-- 
            @if ($book->summary)
            <div class="overflow-scroll">
                <p class="mt-4 sm:mt-0 mb-4 text-xl sm:text-2xl font-bold text-gray-950 dark:text-gray-200 ">Resumen personal</p>
                <p class="mb-4 text-sm sm:text-base text-gray-800 dark:text-gray-300 whitespace-pre-wrap">{!! $book->summary !!}</p>
            </div>
            @endif --}}

            @if ($book->notes)
            <p class="mt-4 sm:mt-0 mb-4 text-xl sm:text-2xl font-bold text-gray-950 dark:text-gray-200 ">Notas personales</p>
            <p class="mb-4 text-sm sm:text-base text-gray-800 dark:text-gray-300  whitespace-pre-wrap">{!! $book->notes !!}</p>
            @endif

            <div class="flex gap-2 items-center">

                <flux:button wire:click="modalQuote" class="mt-1" size="sm" variant="ghost" color="purple" icon="plus" type="submit"></flux:button>
                <flux:separator text="Citas" />
                
            </div>

            <div class="space-y-3">
                @foreach ($quotes as $quote)
                <div class="flex items-start justify-between bg-gray-50 dark:bg-gray-800 p-3 rounded-lg shadow-sm">
                    <p class="text-gray-700 dark:text-gray-200 text-sm leading-relaxed italic">
                        “{{ $quote->content }}”
                    </p>
        
                    <flux:button wire:click="deleteQuote({{ $quote->id }})" class="ml-3 text-gray-400 hover:text-red-500 transition" size="sm" variant="ghost" color="purple" type="submit">✕</flux:button>
        
                </div>
                @endforeach
            </div>

        </div>

    </div>
        


    {{-- modales para agrear y eliminar lecturas --}}
    <flux:modal name="add-read" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Lectura</flux:heading>
                <flux:text class="mt-2">Agregue una fecha de lectura.</flux:text>
            </div>

            {{-- <flux:textarea wire:model='quoteContent' row="20" label="Cita o Frase" placeholder="Coloque la la cita o frase" resize="vertical"/> --}}
            <div class="grid grid-cols-2 gap-1">
                <flux:input wire:model='start_read' type="date" max="2999-12-31" label="Inicio de lectura" />
                <flux:input wire:model='end_read' type="date" max="2999-12-31" label="Fin de lectura" />
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click="addRead" type="submit" variant="primary">Agregar</flux:button>
            </div>
        </div>
    </flux:modal>


    <flux:modal name="delete-read" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar</flux:heading>

                <flux:text class="mt-2">
                    <p>Desea eliminar esta lectura?.</p>
                    <p>Esta accion no puede revertirse.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click="destroyRead" type="submit" variant="danger">Borrar</flux:button>
            </div>
        </div>
    </flux:modal>

    {{-- modales para agrear y eliminar notas --}}
    <flux:modal name="add-quote" class="md:w-96">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Citas</flux:heading>
                <flux:text class="mt-2">Agregue una cita.</flux:text>
            </div>

            <flux:textarea wire:model='quoteContent' row="20" label="Cita o Frase" placeholder="Coloque la la cita o frase" resize="vertical"/>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click="addQuote" type="submit" variant="primary">Agregar</flux:button>
            </div>
        </div>
    </flux:modal>


    <flux:modal name="delete-quote" class="min-w-[22rem]">
        <div class="space-y-6">
            <div>
                <flux:heading size="lg">Eliminar</flux:heading>

                <flux:text class="mt-2">
                    <p>Desea eliminar esta cita?.</p>
                    <p>Esta accion no puede revertirse.</p>
                </flux:text>
            </div>

            <div class="flex gap-2">
                <flux:spacer />

                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>

                <flux:button wire:click="destroyQuote" type="submit" variant="danger">Borrar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>
