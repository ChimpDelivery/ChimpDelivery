<div id="{{ $id }}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
    <div class="toast-header bg-success">
        <img src="{{ asset('default-app-icon.png') }}" class="rounded mr-2" alt="..." width="16" height="16">
        <strong class="mr-auto text-white">Notification</strong>
        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close" onclick="{{ $onClose }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body bg-white">
        {!! $message !!}
    </div>
</div>
