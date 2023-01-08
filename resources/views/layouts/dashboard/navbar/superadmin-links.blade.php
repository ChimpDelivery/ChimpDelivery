@hasrole('Admin_Super')
    <x-nav-link :href="route('index')">
        General
    </x-nav-link>
    <x-nav-link :href="route('health')">
        {{ __('health::notifications.laravel_health') }}
    </x-nav-link>
    <x-nav-link :href="route('telescope')">
        Telescope
    </x-nav-link>
    <x-nav-link :href="route('horizon.index')">
        Horizon
    </x-nav-link>
@endhasrole
