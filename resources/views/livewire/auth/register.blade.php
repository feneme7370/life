<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingrese sus datos para crear una cuenta')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Nombre del usuario')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Su nombre')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
            autocomplete="email"
            placeholder="su email"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Clave')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('su clave')"
            viewable
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmar clave')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('confirmar su clave')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('Ya posee una cuenta?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar sesion') }}</flux:link>
    </div>
</div>
