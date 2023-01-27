<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            @honeypot

            <!-- Name -->
            <div class="mt-4">
                <x-label for="name" :value="__('Full Name')" />

                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
            </div>

            <!-- Email Address -->
            <div class="mt-4">
                <x-label for="email" :value="__('Email')" :icon="'fa-envelope'"/>

                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-label for="password" :value="__('Password')" :icon="'fa-unlock-alt'" />

                <x-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="off" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm Password')" :icon="'fa-unlock-alt'" />

                <x-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required />
            </div>

            <!-- Invitation Code -->
            <div class="mt-4">
                <x-label for="invite_code" :value="__('Invitation Code')" :icon="'fa-users'" />

                <x-input id="invite_code" class="block mt-1 w-full"
                                type="text"
                                name="invite_code"
                                placeholder="if you don't have, leave it blank." />
            </div>

            <div class="mt-4">
                <x-button class="w-full" style="font-size:1.05rem; min-height: 3rem;">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> {{ __('Sign up') }}
                </x-button>

                <h2 class="divider mt-8 text-sm font-bold text-gray-400">
                    Or
                </h2>

                <div class="flex justify-center mt-4">
                    <x-button class="text-sm" type="button" onclick="location.href='{{ route('register.github') }}'">
                        <i class="fa fa-github fa-lg" aria-hidden="true"></i> Sign up with Github
                    </x-button>
                </div>
                <div class="flex justify-center mt-4">
                    <x-button class="text-sm text-gray-400 hover:text-gray-500 border-gray-400" type="button" onclick="location.href='{{ route('login') }}'">
                        <i class="fa fa-sign-in fa-lg" aria-hidden="true"></i> Back to login
                    </x-button>
                </div>
                <p class="text-sm text-gray-400 mt-4">
                    <i class="fa fa-info-circle" aria-hidden="true"></i> By registering to <b>{{ config('app.name') }}</b>, you agree to the <a target="_blank" href="{{ route('terms') }}" class="underline text-sm text-green-600 hover:text-gray-500"><b>Terms of Service</b></a> and <a target="_blank" href="{{ route('privacy') }}" class="underline text-sm text-green-600 hover:text-gray-500"><b>Privacy Policy</b></a>.
                </p>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
