<div id="{{ $id }}" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-autohide="false">
    <div class="toast-header bg-primary">
        <strong class="mr-auto text-white">
            <i class="fa fa-bell" aria-hidden="true"></i> Notification
        </strong>
        <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast" aria-label="Close" onclick="{{ $onClose }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body bg-white">
        {!! $message !!}
    </div>
</div>
