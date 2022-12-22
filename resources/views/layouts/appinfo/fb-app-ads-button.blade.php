<div class="input-group-append">
    <button class="btn btn-secondary font-weight-bold text-white shadow" type="submit"
            onclick="return confirm('FB App ID gonna be added in app-ads.txt, are you sure?')"
            formaction="{{ route('fb_app_ads', ['id' => $appInfo->id]) }}"
            formmethod="post">
        Init ID
    </button>
</div>
