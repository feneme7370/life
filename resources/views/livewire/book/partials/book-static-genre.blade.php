<div>
    <div class="flex justify-between items-center gap-1">
        <span>{{ $filteredBooks->count() }} libros</span>
        <flux:dropdown>
            <flux:button class="col-span-1 text-center" icon:trailing="chevron-down">{{ $year_start == 1900 ? 'Todos' : $year_start }}</flux:button>

            <flux:menu>
                <flux:menu.radio.group>
                    <flux:menu.radio wire:navigated wire:click="newYear('todo')">Todos</flux:menu.radio>
                    @foreach ($read_years as $read_year)
                        <flux:menu.radio wire:click="newYear({{ $read_year }})">{{ $read_year }}</flux:menu.radio>
                    @endforeach
                </flux:menu.radio.group>
            </flux:menu>
        </flux:dropdown>
    </div>

    <div>
        <flux:separator text="📊 Estadísticas básicas" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 my-2">
            {{-- static totals --}}
            <div>
                <flux:heading>Totales ({{ $filteredBooks->count() }})</flux:heading>
    
                <flux:text class="mt-2"><a>📙 {{ $filteredBooks->count() }} libros</a></flux:text>
                <flux:text class="mt-2"><a>📃 {{ $books_total_pages }} pags.</a></flux:text>
                <flux:text class="mt-2"><a>📇 {{ number_format($books_total_pages / ($filteredBooks->count() == 0 ? 1 : $filteredBooks->count()), 0) }} prom. libros</a></flux:text>
                <flux:text class="mt-2"><a>📅 {{ number_format($books_total_pages / 12, 0) }} prom. mes</a></flux:text>
            </div>
        </div>

        <flux:separator text="⭐ Valoraciones y datos" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 my-2">
            {{-- static rating --}}
            <div>
                <flux:heading>Calificacion ({{ $filteredBooks->count() }})</flux:heading>
    
                @foreach ((collect($filteredBooks)->groupBy('rating')->map->count()->sortKeysDesc()) as $rating => $count)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['star' => $rating]) }}"  
                        >{{ $rating_stars[$rating]}} ({{ $count }})</a>
                    </flux:text>
                @endforeach
                
            </div>

            {{-- static pages --}}
            <div>
                <flux:heading>Paginas ({{ $filteredBooks->count() }})</flux:heading>
    
                <div>
                    @foreach ($pagesBuckets as $key => $pagesBucket)
                        @if ($pagesBucket > 0)
                            <flux:text class="mt-2"><a>{{ $key }}: {{ $pagesBucket }} libros</a></flux:text>
                        @endif
                    @endforeach
                </div>
        
            </div>

        </div>


        <flux:separator text="🏷 Clasificación de lecturas" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
 
            {{-- static category --}}
            <div>
                <flux:heading>Categoria ({{ collect($filteredBooks)->groupBy('category')->count() }})</flux:heading>
    
                @foreach ((collect($filteredBooks)->groupBy('category')->map->count()) as $category => $count)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['category' => $category]) }}"  
                        >{{ $category_book[$category]}} ({{ $count }})</a>
                    </flux:text>
                @endforeach
            </div>

            {{-- static collections --}}
            <div>
                <flux:heading>Sagas ({{ $sagaStats->count() }})</flux:heading>
    
                @foreach($sagaStats as $saga)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['c' => $saga['uuid']]) }}"    
                        >🖊️ {{ $saga['name'] }} ({{ $saga['count'] }})</a>
                    </flux:text>
                @endforeach
                
            </div>
    
            {{-- static genres --}}
            <div>
                <flux:heading>Generos ({{ $genreStats->count() }})</flux:heading>
    
                @foreach($genreStats as $genre)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['g' => $genre['uuid']]) }}"     
                        >📑 {{ $genre['name'] }} ({{ $genre['count'] }})</a>
                    </flux:text>
                @endforeach
                
            </div>
    
            {{-- static language --}}
            <div>
                <flux:heading>Idiomas ({{ collect($filteredBooks)->groupBy('language')->count() }})</flux:heading>
    
                @foreach ((collect($filteredBooks)->groupBy('language')->map->count()) as $language => $count)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['language' => $language]) }}"  
                        >{{ $language_book[$language]}} ({{ $count }})</a>
                    </flux:text>
                @endforeach
                
            </div>
    
            {{-- static format --}}
            <div>
                <flux:heading>Formato ({{ collect($filteredBooks)->groupBy('format')->count() }})</flux:heading>
    
                @foreach ((collect($filteredBooks)->groupBy('format')->map->count()) as $format => $count)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['format' => $format]) }}"  
                        >{{ $format_book[$format]}} ({{ $count }})</a>
                    </flux:text>
                @endforeach
                
            </div>
    
        </div>

        <flux:separator text="📖 Listado" />
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 my-2">
            {{-- static list titles --}}
            <div>
                <flux:heading>Leidos ({{ $filteredBooks->count() }})</flux:heading>
    
                @foreach($bookStats as $book)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline" 
                            href="{{ route('book_view', ['uuid' => $book['uuid']]) }}"
                        >📖 {{ $book['title'] }}</a>
                    </flux:text>
                @endforeach
                
            </div>

            {{-- static authors --}}
            <div>
                <flux:heading>Autores ({{ $authorStats->count() }})</flux:heading>
    
                @foreach($authorStats as $author)
                    <flux:text class="mt-2">
                        <a
                            class="hover:underline"
                            href="{{ route('books_library', ['a' => $author['uuid']]) }}" 
                        >🖊️ {{ $author['name'] }} ({{ $author['count'] }})</a>
                    </flux:text>
                @endforeach
                
            </div>
    
        </div>

    </div>

    <flux:separator text="📌 Pendientes de comentar" />
    {{-- static list comment --}}
    <div>
        <flux:heading>Pendientes de comentar ({{ $pendingComments->count() }})</flux:heading>

        @foreach($pendingComments as $book)
            {{-- @if (!$book->notes) --}}
                <flux:text class="mt-2">
                    <a
                        class="hover:underline" 
                        href="{{ route('book_view', ['uuid' => $book['uuid']]) }}"
                    >🗒️ {{ $book['title'] }}</a>
                </flux:text>
            {{-- @endif --}}
        @endforeach
        
    </div>

    <flux:separator text="🚫 Abandonados" />
    {{-- static list pend reads --}}
    <div>
        <flux:heading>Abandonados ({{ $abandonatedBooks->count() }})</flux:heading>

        @foreach($abandonatedBooks as $book)
            {{-- @if ( $book->status === 5) --}}
                <flux:text class="mt-2">
                    <a
                        class="hover:underline" 
                        href="{{ route('book_view', ['uuid' => $book['uuid']]) }}"
                    >🗒️ {{ $book['title'] }}</a>
                </flux:text>
            {{-- @endif --}}
        @endforeach
        
    </div>

        <flux:separator text="📊 Grafico por mes" />

    <div>
        @foreach ($filteredBooksMonths as $key => $month)
            <p>
                <x-pages.dictionary.month :number="$key" type="short" />: {{ str_repeat('📖', $month) }}
            </p>
        @endforeach
    </div>

    <flux:separator text="📖 Pendientes totales a leer" />
    {{-- static list pend reads --}}
    <div>
        <flux:heading>Pendientes de leer ({{ $pendingBooks->count() }})</flux:heading>

        @foreach($pendingBooks as $book)
            {{-- @if (!$book->reads->count()) --}}
                <flux:text class="mt-2">
                    <a
                        class="hover:underline" 
                        href="{{ route('book_view', ['uuid' => $book['uuid']]) }}"
                    >🗒️ {{ $book['title'] }}</a>
                </flux:text>
            {{-- @endif --}}
        @endforeach
        
    </div>

</div>
