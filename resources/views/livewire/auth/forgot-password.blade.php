 <div class="flex flex-col gap-6">
    <x-auth-header :title="__('Olvido su clave')" :description="__('Ingrese su email para recibir un link donde podra reestablecer su clave')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email')"
            type="email"
            required
            autofocus
            placeholder="su emal"
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Enviar link para restaurar clave') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('Volver a') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar sesion') }}</flux:link>
    </div>
</div>
