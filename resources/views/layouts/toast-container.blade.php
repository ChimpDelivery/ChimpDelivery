<div class="toast-container">
    @php
        $dailyMessage = Auth::user()->hasRole('User') 
        ? 'Welcome to <b>' . config('app.name') . '</b>, have a nice day.'
        : 'Hi <span class="text-capitalize font-weight-bold text-dark">' 
            . Auth::user()->name 
            . '</span>, welcome back to <span class="text-capitalize font-weight-bold text-dark">' 
            . Auth::user()->workspace->name 
            . ' Workspace</span>.';
    @endphp

    @include('layouts.toast', [ 
        'id' => 'toast-talus', 
        'message' => $dailyMessage,
        'onClose' => 'setToastCookie()'
    ]) 

    @includeWhen(session()->has('success'), 'layouts.toast', [ 
        'id' => 'toast-flash', 
        'message' => session('success'),
        'onClose' => '',
    ])
</div>
