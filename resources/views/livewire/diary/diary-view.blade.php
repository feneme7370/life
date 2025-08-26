<div>
    {{-- datos de la pagina --}}
    <div class="relative mb-6 w-full">
        <flux:heading size="xl" level="1">{{ ucfirst($title['singular']) }}</flux:heading>
        <flux:subheading size="lg" class="mb-6">Ver {{ $title['singular'] }}</flux:subheading>
        <flux:separator variant="subtle" />
    </div>

    <flux:icon.loading class="block w-full" wire:loading.delay />
    
    {{-- <livewire:book.book-edit :summary="$book->summary" :notes="$book->notes"> --}}

    {{-- mensaje de success --}}
    @session('success')
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false }, 3000)"
            class="fixed top-5 right-5 bg-green-600 text-gray-50 text-sm p-4 rounded-lg shadow"
            role="alert"
        >
            <p>{{ $value }}</p>
        </div>
    @endsession

    <div class="w-full ">


        <div class="sm:m-3 p-4 bg-gray-50 border border-gray-200 dark:bg-gray-800 dark:border-gray-900 rounded-lg shadow-sm sm:p-8">

            <div class="grid grid-cols-1 gap-1">
                <div class="flex justify-between items-center gap-1">
                    <p class="flex items-center gap-2 text-sm mb-1">{{ $diary->day }} {{ $humor_status[$diary->humor] ?? 'Desconocido' }}</p>
                    <a href="{{ route('diary_edit', ['uuid' => $diary->uuid]) }}">
                        <flux:button variant="ghost" color="blue" icon="pencil-square" size="sm"></flux:button>
                    </a>
                </div>
                <flux:separator text="Notas" />
                <p class="text-sm mb-1">{{ $diary->title }}</p>
                <span class="text-xs">{!! $diary->content !!}</span>
            </div>

        </div>

    </div>
</div>
