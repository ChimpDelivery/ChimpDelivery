<a class="navbar-brand font-weight-bold" href="{{ route('index') }}">
    <img src="{{ asset('Talus_icon.ico') }}" alt="" width="30" height="30"/>
    @hasanyrole('User|Admin_Super')
        {{ config('app.name') }}
    @else
        <span class="text-capitalize font-weight-bold">{{ Auth::user()->workspace->name }}</span>
    @endhasanyrole
</a>
