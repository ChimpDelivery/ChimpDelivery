<div class="input-group-append">
    <button class="btn alert-primary font-weight-bold" type="submit"
            onclick="return confirm('FB App ID gonna be added in app-ads.txt, are you sure?')"
            formaction="{{ route('fb_app_ads', ['id' => $appInfo->id]) }}"
            formmethod="post"
            data-toggle="tooltip"
            data-placement="bottom"
            title="App-Ads.txt integration">
        <i class="fa fa-plus" aria-hidden="true"></i>
    </button>
</div>
