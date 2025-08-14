<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['singular']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Ver {{ $title['singular'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>


<div class="w-full ">


    <div class="sm:m-3 p-4 bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:border-gray-900 rounded-lg shadow-sm sm:p-8">

        <div class="flex items-center justify-between mb-4 ">
            <div class="flex items-center gap-1">
                <h5 class="text-xl font-bold leading-none text-gray-900 dark:text-gray-300">Libro</h5>
            </div>
            @if (session('status'))
                <div class="alert alert-success text-sm font-bold leading-none text-red-900 ">
                    {{ session('status') }}
                </div>
            @endif</h5>
            <div>
                
                <a href="{{ route('books_library') }}" class="text-sm font-medium text-gray-600 dark:text-gray-300 hover:underline ">
                    Volver
                </a>
            </div>
       </div>

        <img src="{{ $book->cover_image_url }}" class="w-full sm:w-auto sm:h-96 mx-auto mb-1 sm:mb-5" alt="">

        <div class="flex justify-between items-start gap-1">
            <div class="mt-4 sm:mt-0 mb-4 ">
                <h5 class="text-xl sm:text-2xl font-bold text-gray-950 dark:text-gray-300 ">{{ $book->title }}</h5>
                <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300  font-light italic">{{ $book->original_title }}</p>
            </div>
            {{-- <a href="{{ route('book_edit', ['type' => $book->book_type, 'uuid' => $book->uuid]) }}" class="font-medium text-gray-600  hover:underline"><x-pages.buttons.edit-text/></a> --}}
        </div>
        
        <flux:separator text="Datos" />

        <p class="mt-1 mb-3 text-base sm:text-lg text-gray-800"><span class="text-gray-950 dark:text-gray-300  font-bold">{{ \Carbon\Carbon::parse($book->release_date)->year }}</span>
            @foreach ($book->book_subjects as $item)
            - <a 
                class="italic hover:underline dark:text-gray-300 " 
                href="{{ route('books_library', ['a' => $item->uuid]) }}"
            >{{ $item->name }}</a>
            @endforeach
        </p>

        @if ($book->number_collection)
        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Volumen:</span> {{$book->number_collection}}</p>
        @endif
        @if ($book->pages)
        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Paginas:</span> {{ $book->pages }}</p>
        @endif

        @if ($book->format)
        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Formato:</span> {{ $format_book[$book->format] ?? 'Desconocido' }}</p>
        @endif

        @if ($book->media_type)
        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Tipo:</span> {{ $media_type_content[$book->media_type] ?? 'Desconocido' }}</p>
        @endif

        <p class="mb-4 text-sm sm:text-base text-gray-800 dark:text-gray-300  whitespace-pre-wrap">{{ $book->synopsis }}</p>

        <flux:separator text="Asociaciones" />

        @if (!$book->book_collections->isEmpty())
        <p class="mt-1 text-sm sm:text-base text-gray-800"><span class="text-gray-950 dark:text-gray-300  font-bold">Coleccion:</span></p>
        <div class="mb-2">
            @foreach ($book->book_collections as $item)
                <a 
                    class="bg-purple-900 text-purple-50 text-xs font-medium me-2 px-2.5 py-0.5 rounded-lg" 
                    href="{{ route('books_library', ['c' => $item->uuid]) }}"
                >{{ $item->name }}</a>
            @endforeach
        </div>
        @endif

        @if (!$book->book_genres->isEmpty())
        <p class="mt-1 text-sm sm:text-base text-gray-800"><span class="text-gray-950 font-bold">Generos:</span></p>
        <div class="mb-2">
            @foreach ($book->book_genres as $item)
                <a 
                    class="bg-purple-900 text-purple-50 text-xs font-medium me-2 px-2.5 py-0.5 rounded-lg" 
                    href="{{ route('books_library', ['g' => $item->uuid]) }}"
                >{{ $item->name }}</a>
            @endforeach
        </div>
        @endif

        @if (!$book->book_tags->isEmpty())
        <p class="mt-1 text-sm sm:text-base text-gray-800"><span class="text-gray-950 font-bold">Etiquetas:</span></p>
        <div class="mb-2">
            @foreach ($book->book_tags as $item)
                <flux:badge variant="solid" color="violet">
                    <a 
                        {{-- class="bg-purple-900 text-purple-50 text-xs font-medium me-2 px-2.5 py-0.5 rounded-lg"  --}}
                        href="{{ route('books_library', ['t' => $item->uuid]) }}"
                    >{{ $item->name }}</a>
                </flux:badge>
            @endforeach
        </div>
        @endif

        <flux:separator text="Lecturas" />

        @if ($book->end_date)
        <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300  italic">1° Lectura</p>
                <div class="px-3 border-l-4 border-purple-800">
                    <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $book->start_date }} - {{ $book->end_date }} en {{ \Carbon\Carbon::parse($book->start_date)->diffInDays($book->end_date) }} dias</p>
                </div>
        @endif
        
        @if ($book->end_date_two)
        <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300  italic">2° Lectura</p>
                <div class="px-3 border-l-4 border-purple-800">
                    <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $book->start_date_two }} - {{ $book->end_date_two }} en {{ \Carbon\Carbon::parse($book->start_date_two)->diffInDays($book->end_date_two) }} dias</p>
                </div>
        @endif
        
        @if ($book->end_date_three)
        <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300  italic">3° Lectura</p>
                <div class="px-3 border-l-4 border-purple-800">
                    <p class="mb-2 text-xs sm:text-base text-gray-800 dark:text-gray-300 ">{{ $book->start_date_three }} - {{ $book->end_date_three }} en {{ \Carbon\Carbon::parse($book->start_date_three)->diffInDays($book->end_date_three) }} dias</p>
                </div>
        @endif

        <flux:separator text="Anotaciones Personales" />
        @if ($book->summary)
        <p class="mt-4 sm:mt-0 mb-4 text-xl sm:text-2xl font-bold text-gray-950 dark:text-gray-400 ">Resumen personal</p>
        <p class="mb-4 text-sm sm:text-base text-gray-800 dark:text-gray-300  whitespace-pre-wrap">{!! $book->summary !!}</p>
        @endif

        @if ($book->notes)
        <p class="mt-4 sm:mt-0 mb-4 text-xl sm:text-2xl font-bold text-gray-950 dark:text-gray-400 ">Notas personales</p>
        <p class="mb-4 text-sm sm:text-base text-gray-800 dark:text-gray-300  whitespace-pre-wrap">{!! $book->notes !!}</p>
        @endif

        @if ($book->rating)
        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Valoracion:</span> {{ $rating_stars[$book->rating] ?? 'Desconocido' }}</p>
        @endif
        @if ($book->status)
        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Estado:</span> {{ $status_book[$book->status] ?? 'Desconocido' }}</p>
        @endif

        <p class="mb-2 text-sm sm:text-base text-gray-800 dark:text-gray-300 "><span class="text-gray-950 dark:text-gray-400  font-bold">Favorito:</span> {{ $book->is_favorite ? 'Si' : 'No' }}</p>

        </div>
        
</div>

</div>
