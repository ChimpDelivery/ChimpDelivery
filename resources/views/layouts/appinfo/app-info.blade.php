<div class="img-notify-item">
    <a class="text-muted" href="https://appstoreconnect.apple.com/apps/{{ $appInfo->appstore_id }}/testflight">
        <span class="img-notify-badge" data-toggle="tooltip" data-placement="bottom" title="App ID">{{ $appInfo->id }}</span>
        <img class="img" src="{{ asset('Talus_icon.ico') }}" alt="..." width="50" height="50" />
    </a>
</div>
<div class="container text-center">
    <a class="text-white font-weight-bold" href="{{ $appInfo->git_url }}">
        {{ Str::limit($appInfo->project_name, 14) }}
    </a>
</div>
