<div class="container text-center">
    <a class="text-muted" href="{{ $appInfo->git_url }}">
        @if (file_exists(public_path("images/app-icons/{$appInfo->app_icon}")) && !empty($appInfo->app_icon))
            <img class="img-thumbnail" src="{{ asset('images/app-icons/'.$appInfo->app_icon) }}" alt="..."width="48px" height="48px" />
        @else
            <img class="img-thumbnail" src="{{ asset('Talus_icon.ico') }}" alt="..." style="max-width:50px;" />
        @endif
    </a>
</div>
<div class="container text-center">
    <a class="text-dark font-weight-bold" href="https://appstoreconnect.apple.com/apps/{{ $appInfo->appstore_id }}/testflight">
        {{ Str::limit($appInfo->app_name, 14) }}
    </a>
</div>
