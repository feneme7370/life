<div>

    <div class="">
        @foreach ($diaries as $diary)
    
            <div class="my-1 text-sm">
                <p><span class="font-bold"></span>{{ $diary->day }} - {{ $humor_status[$diary->humor] ?? 'Desconocido'}}</p>
                <p class="text-sm my-1">{{ $diary->title }}</p>
                <p><span class="text-xs">{!! $diary->content !!}</span></p>

            <!-- Mostrar imágenes guardadas -->
            @if ($diary->images->count())
                <div class="flex flex-wrap gap-1 mt-5 justify-center">
                    @foreach ($diary->images as $image)
                        <div class="relative group">
                            <img src="{{ asset($image->path) }}" class="rounded-lg shadow h-16 w-16 md:w-32 md:h-32 object-cover" />
                        </div>
                    @endforeach
                </div>
            @endif
                
                <p>-------------------------------------------------------------------------------------------</p>
            </div>
        @endforeach
    </div>
</div>
