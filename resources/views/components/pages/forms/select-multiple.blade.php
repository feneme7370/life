@props([
    'model', // Nombre del modelo (ej: 'Collection', 'Tag', 'Author')
    'relation', // Relación en el modelo principal (ej: 'collections', 'authors')
    'selected' => [], // Elementos seleccionados (IDs)
    'label' => 'Seleccionar', // Etiqueta del campo
    'items' => [], // Etiqueta del campo
    'wire_model' => '', // Etiqueta del campo
])

@php
    // $items = app("App\\Models\\" . $model)::select('id', 'name')->get()->toArray();
@endphp

<div x-data="{ selected: @entangle($attributes->wire('model')), allItems: @js($items), search: '' }">

    <flux:input label="{{ $label }}" wire:model="{{ $wire_model }}" x-model="search" placeholder="Buscar..." />

    <!-- Lista de opciones -->
    <ul class="p-2 max-h-40 overflow-y-auto text-sm">
        <template x-for="item in allItems.filter(i => i.name.toLowerCase().includes(search.toLowerCase()))" :key="item.id">
            <li class="cursor-pointer p-1 hover:bg-gray-200 rounded-lg"
                @click="selected.includes(item.id) ? selected.splice(selected.indexOf(item.id), 1) : selected.push(item.id)">
                <span x-text="item.name"></span>
                <span x-show="selected.includes(item.id)" class="ml-1 text-green-500 font-bold">✔</span>
            </li>
        </template>
    </ul>

    <!-- Elementos seleccionados -->
    <div class="mt-1 text-sm">
        <template x-for="id in selected" :key="id">
            <span class="bg-gray-700 text-gray-200 text-xs font-bold me-2 px-2.5 py-0.5 rounded-lg">
                <button @click="selected.splice(selected.indexOf(id), 1)" class="ml-1 cursor-pointer hover:text-gray-400">X</button>
                <span x-text="allItems.find(i => i.id == id)?.name"></span>
            </span>
        </template>
    </div>
</div>