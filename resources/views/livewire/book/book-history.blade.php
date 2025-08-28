<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Ultimos {{ $title['plural'] }} leidos</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    <div>
        <livewire:book.partials.book-static-genre />
    </div>


    <flux:separator text="🖨️ Exportar" />
    <a href="{{ route('book_list_history') }}">
        <flux:button size="sm" variant="ghost" color="purple" icon="plus" size="sm">Listado completo para copiar</flux:button>
    </a>
</div>
