<div class="space-y-4 my-1">
    <div class="flex gap-2">
        <input 
            type="text" 
            wire:model.debounce.500ms="query" 
            wire:keydown.enter="search" 
            placeholder="Buscar autor..."
            class="border p-2 rounded w-full"
        >
        <button wire:click="search" class="bg-blue-500 text-white px-4 py-2 rounded">
            Buscar
        </button>
    </div>

    @if (!empty($this->authors))
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @forelse ($this->authors as $author)

                <div class="border p-4 rounded shadow">

                    @if ($author['picture'])
                        <img 
                            src="{{ $author['picture'] }}" 
                            alt="{{ $author['name'] }}" 
                            class="w-16 h-auto mb-2 object-cover"
                        >
                    @endif

                    <div>
                        <h2 class="text-lg font-bold">{{ $author['name'] }}</h2>
                        <p class="text-sm text-gray-600">Nacimiento: {{ $author['birth_date'] }}</p>
                        @if($author['bio'])
                            <p class="mt-2 text-gray-500 italic line-clamp-3">{{ $author['bio'] }}</p>
                        @else
                            <p class="mt-2 text-gray-500 italic line-clamp-3">Sin biografía</p>
                        @endif
                    </div>
                    
                    <flux:button icon="plus" size="sm"  wire:click="generate_autor('{{ json_encode($author) }}')">Crear</flux:button>
                </div>

            @empty
                <p class="text-gray-500">No se encontraron autores</p>
            @endforelse
        </div>
    @endif
</div>
