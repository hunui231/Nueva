<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
            <img src="{{ asset('theme/images/icon/logo2.png') }}" class="w-20 h-20" alt="Logo">
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Correo')" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $email)" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Contraseña Nueva')" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirmar Contraseña')" />
                <x-input id="password_confirmation" class="block mt-1 w-full"
                                    type="password"
                                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Restablecer Contraseña') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>