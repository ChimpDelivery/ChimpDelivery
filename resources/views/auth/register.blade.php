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
            <div>
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
                <div class="text-sm text-green-600">
                    <x-input id="contracts" name="contracts" type="checkbox" style="margin-bottom: 1px;" />
                    <label for="contracts" class="font-bold">
                        &nbsp; I agree to the <a target="_blank" href="{{ route('terms') }}" class="underline text-sm text-green-600 hover:text-gray-500">Terms of Service</a> and <a target="_blank" href="{{ route('privacy') }}" class="underline text-sm text-green-600 hover:text-gray-500">Privacy Policy</a>.
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-400 hover:text-gray-500" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-button class="ml-4">
                    {{ __('Register') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
