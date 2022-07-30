<div class="toast-container">
    <div id="toast-talus" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
        <div class="toast-header bg-success">
            <img src="{{ asset('Talus_icon.ico') }}" class="rounded mr-2" alt="..." width="16" height="16">
            <strong class="mr-auto text-white">Talus Dashboard</strong>
            <small class="text-white">7 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" onclick="setToastCookie()">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Welcome to Talus Dashboard, have a nice day.
        </div>
    </div>

    @if (session()->has('success'))
    <div id="toast-flash" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
        <div class="toast-header bg-success">
            <img src="{{ asset('Talus_icon.ico') }}" class="rounded mr-2" alt="..." width="16" height="16">
            <strong class="mr-auto text-white">Notification</strong>
            <small class="text-white">1 mins ago</small>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
    @endif
</div>
