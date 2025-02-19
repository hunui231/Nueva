<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="{{ asset('theme/images/icon/logo2.png') }}" class="w-20 h-20" alt="Logo">
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('¿Olvidaste tu contraseña? No hay problema. Escriba su dirección de correo electrónico y se le notificará al administrador del sistema.') }}
        </div>

        <!-- Mensaje de Estado (Éxito) -->
        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Mensaje de Error -->
        @if (session('error'))
            <div class="mb-4 text-sm text-red-600">
                {{ session('error') }}
            </div>
        @endif

        <!-- Formulario para ingresar el correo -->
        <form method="POST" action="{{ route('enviar.correo') }}">
            @csrf

            <!-- Campo para correo -->
            <div>
                <x-label for="email" :value="__('Correo')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Botón de Enviar -->
            <div class="flex items-center justify-end mt-4">
                <x-button class="button-enviar" >
                    {{ __('Enviar') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>



