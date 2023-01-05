<div class="input-group-append">
    <button wire:loading.remove
            wire:click="integrate"
            class="btn btn-success"
            type="button"
            onclick="return confirm('FB App ID gonna be added in app-ads.txt, are you sure?') || event.stopImmediatePropagation()"
            data-toggle="tooltip"
            data-placement="bottom"
            title="App-Ads.txt integration">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
    <div wire:loading class="btn alert-warning font-weight-bold">
        Wait...
    </div>
</div>
