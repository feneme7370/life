<div>

    <flux:radio.group wire:model.live="list_all_data" variant="segmented" size="sm">
        <flux:radio value="completa" label="Completa" />
        <flux:radio value="ligera" label="Ligera" />
        <flux:radio value="redes" label="Leidos para Redes" />
    </flux:radio.group>


    @if ($list_all_data == 'completa')
        <div class="divide-gray-800 divide-y">
            @foreach ($books as $book)
        
                <div class="my-1 text-sm">
                    <p><span class="font-bold">| Titulo: </span>{{ $book->title }}</p>
                    <p><span class="font-bold">| Titulo Original: </span>{{ $book->original_title }}</p>
        
                    <p>
                        <span class="font-bold">| Autor(es):</span>
                        
                        @foreach ($book->book_subjects as $subject)
                            <span>{{ $subject->name }}</span>
                        @endforeach

                        <span class="font-bold">| Publicacion: </span>{{ \Carbon\Carbon::parse($book->release_date)->year }}
                    </p>
        
                    <p><span class="font-bold">| Link Imagen: </span>{{ $book->cover_image_url }}</p>
                    <p>
                        <span class="font-bold">| Saga: </span>
                        
                        @foreach ($book->book_collections as $collection)
                            <span>{{ $collection->name }}</span>
                        @endforeach
                        <span class="font-bold">| Volumen: </span>
                        {{ $book->number_collection }}
                    </p>
                    <p>
                        <span class="font-bold">| Paginas: </span>{{ $book->pages }}
                        <span class="font-bold"> | </span>{{ $media_type_content[$book->media_type] ?? 'Desconocido' }}
                        <span class="font-bold"> | </span>{{ $format_book[$book->format] ?? 'Desconocido' }}
                        <span class="font-bold"> | </span>{{ $category_book[$book->category] ?? 'Desconocido' }}
                        <span class="font-bold"> | </span>{{ $language_book[$book->language] ?? 'Desconocido' }}</p>
                    
                    <p><span class="font-bold">| Sinopsis: </span>{{ $book->synopsis }}</p>
                    
                    <p>
                        <span class="font-bold">| Generos: </span>
                        
                        @foreach ($book->book_genres as $genre)
                        <span>{{ $genre->name }}</span>
                        @endforeach
                    </p>
                    <p>
                        <span class="font-bold">| Etiquetas: </span>
                        
                        @foreach ($book->book_tags as $tag)
                        <span>{{ $tag->name }}</span>
                        @endforeach
                    </p>
                    
                    <p>
                        <span class="font-bold">| Lecturas: </span>
                        
                        @foreach ($book->reads as $read)
                        <span>{{ $read->start_read }} - {{ $read->end_read }}</span> |
                        @endforeach
                    </p>

                    <p><span class="font-bold">| Valoracion: </span>{{ $rating_stars[$book->rating] ?? 'Desconocido' }} | <span class="font-bold">Favorito: </span>{{ $book->is_favorite ? 'Si' : 'No' }}</p>
                    {{-- <p><span class="font-bold">| Resumen: </span>{!! $book->summary !!}</p> --}}
                    <p class=" whitespace-pre-wrap break-words"><span class="font-bold">| Notas: </span>{!! $book->notes !!}</p>
        
                    <p>
                        <span class="font-bold">| Citas: </span>
                        @foreach ($book->quotes as $quote)
                            <span>{{ $quote->content }}</span>
                        @endforeach
                    </p>
                </div>
            @endforeach

            {{-- paginacion --}}
            <div class="mt-3">
                {{ $books->links() }}
            </div>
        </div>
    @endif

    @if ($list_all_data == 'ligera')
        <div class="divide-gray-800 divide-y">
            @foreach ($books as $book)
        
                <div class="my-1 text-sm">
                    <p>
                        <span class="font-bold">| {{ $book->title }}. </span>
                        <span>
                            @foreach ($book->book_subjects as $subject)
                                <span>{{ $subject->name }}</span>
                            @endforeach
                        </span>
                        <span> ({{ \Carbon\Carbon::parse($book->release_date)->year }})</span>
                    </p>

                    {{-- @if ($book->summary)
                        <p><span class="font-bold">| Resumen: </span>{!! $book->summary !!}</p>
                    @endif --}}

                    @if ($book->notes)
                        <p class=" whitespace-pre-wrap break-words"><span class="font-bold">| Notas: </span>{!! $book->notes !!}</p>
                    @endif
        
                    @if ($book->quotes->count() > 0)
                        <p>
                            <span class="font-bold">| Citas: </span>
                            @foreach ($book->quotes as $quote)
                                <span>{{ $quote->content }}</span>
                            @endforeach
                        </p>
                    @endif
                </div>
            @endforeach
            
            <div class="mt-5">
                <p class="font-bold">Indice</p>
                @foreach ($books as $book)
                    <p>{{ $book->title }}</p>
                @endforeach
            </div>
        </div>

        {{-- paginacion --}}
        <div class="mt-3">
            {{ $books->links() }}
        </div>
    @endif

    @if ($list_all_data == 'redes')
        <div class="divide-gray-800 divide-y">
            @foreach ($books as $book)
        
                <div class="my-1 text-sm">
                    <img src="{{ $book->cover_image_url }}" class="w-full sm:w-auto sm:h-52 mb-1" alt="">
                    <p><span class="font-bold">📘 Título: </span>{{ $book->title }}</p>
                    <p><span class="font-bold">📚 Título Original: </span>{{ $book->original_title }}</p>
                    <p><span class="font-bold">✍️ Autor(es): </span>
                        @foreach ($book->book_subjects as $subject)
                            <span>{{ $subject->name }}</span>
                        @endforeach
                    </p>
                    <p><span class="font-bold">📔 Genero(s): </span>
                        @foreach ($book->book_genres as $genre)
                            <span>{{ $genre->name }} {{ $book->book_genres->count() > 1 ? ' | ' : '' }}</span>
                        @endforeach
                    </p>
                    <p><span class="font-bold">🏷️ Etiqueta(s): </span>
                        @foreach ($book->book_tags as $tag)
                            <span>{{ $tag->name }} {{ $book->book_tags->count() > 1 ? ' | ' : '' }}</span>
                        @endforeach
                    </p>
                    <p><span class="font-bold">📗 Saga: </span>
                        @if ($book->book_collections->count() > 0)
                            @foreach ($book->book_collections as $collection)
                                <span>{{ $collection->name }}</span>
                            @endforeach
                        @else
                            <span>Ninguna</span>
                        @endif
                        @if ($book->book_collections->count() > 0)
                            <span class="font-bold">(Vol): </span>
                            {{ $book->number_collection }}
                        @endif
                    </p>
                    <p><span class="font-bold">📅 Publicación: </span>{{ \Carbon\Carbon::parse($book->release_date)->year }}</p>
                    <p><span class="font-bold">❤️ Favorito: </span>{{ $book->is_favorite ? 'Si' : 'No' }}</p>
                    <p><span class="font-bold">📄 Páginas: </span>{{ $book->pages }}</p>
                    <p><span class="font-bold">⭐ Calificación: </span>{{ $rating_stars[$book->rating] ?? 'Desconocido' }}</p>
                    <p><span class="font-bold">📖 Fecha de lectura: </span>
                        @foreach ($book->reads as $read)
                        <span>I: {{ $read->start_read }} - F: {{ $read->end_read }}{{ $book->reads->count() > 1 ? ' | ' : '' }}</span>
                        @endforeach
                    </p>
                    <p class="whitespace-pre-wrap break-words"><span class="font-bold">📜 Sinopsis: </span>{{ $book->synopsis }}</p>
                    <p class="whitespace-pre-wrap break-words"><span class="font-bold">🖊️ Notas: </span>{!! $book->notes !!}</p>
                    
                    <p><span class="font-bold">| Link Imagen: </span>{{ $book->cover_image_url }}</p>

                </div>
            @endforeach

            {{-- paginacion --}}
            <div class="mt-3">
                {{ $books->links() }}
            </div>
        </div>
    @endif
    
</div>
