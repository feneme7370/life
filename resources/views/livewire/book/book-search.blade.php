<div class="space-y-4 my-1">
    <div class="flex gap-2">
        <input 
            type="text" 
            wire:model.debounce.500ms="query"
            wire:keydown.enter="search" 
            placeholder="Buscar libro..."
            class="border p-2 rounded w-full"
        >
        <flux:button size="sm" variant="primary" color="purple" icon="plus" wire:click="search">Buscar</flux:button>
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    @if(!empty($results))
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse($results as $book)
                <div class="border p-4 rounded shadow">

                    @if($book['thumbnail'])
                        <img 
                            src="{{ $book['thumbnail'] }}" 
                            alt="Portada" 
                            class="w-16 h-auto mb-2 object-cover"
                        >
                    @endif

                    <div>
                        <h2 class="text-lg font-bold">{{ $book['title'] }}</h2>
                        <p class="text-sm text-gray-600"><strong>Autores:</strong> {{ implode(', ', $book['authors']) }}</p>
                        <p class="text-sm text-gray-600"><strong>Publicado:</strong> {{ $book['published_date'] }}</p>
                        <p class="text-sm text-gray-600"><strong>Páginas:</strong> {{ $book['pages'] ?? 'N/A' }}</p>
                        <p class="mt-2 text-gray-500 italic line-clamp-3"><strong>Sinopsis:</strong> {!! $book['description'] ?: 'N/A' !!}</p>
                    </div>
                    
                    <a href="{{ route('book_create', ['book_api' => $book]) }}">
                        <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Nuevo</flux:button>
                    </a>
                    {{-- <flux:button  size="sm" variant="primary" color="purple" icon="plus" wire:click="generate('{{ json_encode($book) }}')">Crear</flux:button> --}}
                </div>

            @empty
                <p class="text-gray-500">No se encontraron libros</p>
            @endforelse
        </div>
    @endif
</div>
