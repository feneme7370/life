<div>

    <div class="">
        @foreach ($diaries as $diary)
    
            <div class="my-1 text-sm">
                <p><span class="font-bold"></span>{{ $diary->day }} - {{ $humor_status[$diary->humor] ?? 'Desconocido'}}</p>
                <p class="text-sm my-1">{{ $diary->title }}</p>
                <p><span class="text-xs">{!! $diary->content !!}</span></p>
                
                <p>-------------------------------------------------------------------------------------------</p>
            </div>
        @endforeach
    </div>
</div>
