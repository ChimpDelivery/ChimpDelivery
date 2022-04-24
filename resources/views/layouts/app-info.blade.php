<div class="col">
    @if (file_exists(public_path("images/app-icons/{$appInfo->app_icon}")) && !empty($appInfo->app_icon))
        <img src="{{ asset('images/app-icons/'.$appInfo->app_icon) }}"
             width="100px" height="100px" alt="..." class="img-thumbnail"/>
    @else
        <img src="{{ asset('Talus_icon.ico') }}" width="100px" height="100px"
             alt="..." class="img-thumbnail"/>
    @endif
</div>
<div class="col">
    <a class="text-dark font-weight-bold"
       href="https://appstoreconnect.apple.com/apps/{{ $appInfo->appstore_id }}/testflight">
        {{ $appInfo->app_name }}
    </a>
    <p>
        <a class="text-muted" href="{{ $appInfo->git_url }}">
            (Github)
        </a>
    </p>
</div>
