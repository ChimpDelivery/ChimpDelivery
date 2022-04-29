<div class="container text-center">
    @if (file_exists(public_path("images/app-icons/{$appInfo->app_icon}")) && !empty($appInfo->app_icon))
        <img src="{{ asset('images/app-icons/'.$appInfo->app_icon) }}"
             width="48px" height="48px"
             alt="..." class="img-thumbnail"/>
    @else
        <a class="text-muted" href="{{ $appInfo->git_url }}">
            <img src="{{ asset('Talus_icon.ico') }}" class="img-thumbnail" alt="..." style="max-width:50px;" />
        </a>
    @endif
</div>
<div class="container text-center">
    <a class="text-dark font-weight-bold" href="https://appstoreconnect.apple.com/apps/{{ $appInfo->appstore_id }}/testflight">
        {{ $appInfo->app_name }}
    </a>
</div>
