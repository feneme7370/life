<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['plural']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Administrar {{ $title['plural'] }} API</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />

    <flux:separator text="buscar libro desde API" />
    <livewire:book.book-search />

    <flux:separator text="buscar autor desde API" />
    <livewire:book.book-author-search />


    {{-- modales de crear y editar --}}
    <livewire:book.book-create>
    <livewire:subject.subject-create>
</div>
