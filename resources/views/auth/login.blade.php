<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}">
            @csrf
            @honeypot

            <!-- Email Address -->
            <div>
                <x-label for="email" :value="__('Email')" :icon="'fa-envelope'" />

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" :icon="'fa-unlock-alt'" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="off" />
            </div>

            <!--<div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>!-->

            <div class="flex items-center justify-between mt-4">
                <x-button class="w-full p-2">
                    <i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> {{ __('Log in') }}
                </x-button>
            </div>
            <div class="flex justify-between mt-4">
                @if (Route::has('register'))
                    <a class="underline text-sm text-gray-400 hover:text-gray-500" href="{{ route('register') }}">
                        {{ __('Sign up') }}
                    </a>
                @endif
                @if (Route::has('password.request'))
                    <a class="underline text-sm text-gray-400 hover:text-gray-500 ml-auto" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            <div>
                <h2 class="divider mt-4 text-sm font-bold text-white">
                    Log in with
                </h2>

                <div class="flex justify-center mt-4">
                    <x-button class="text-sm" type="button" onclick="location.href='{{ route('register.github') }}'">
                        <i class="fa fa-github fa-lg" aria-hidden="true"></i> GitHub
                    </x-button>
                </div>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
