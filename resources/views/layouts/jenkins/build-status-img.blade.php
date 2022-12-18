@php
    $ws = Auth::user()->workspace->githubSetting->organization_name;
    $buildNumber = $appInfo->jenkins_data?->id;

    $url = implode('/', [
        config('jenkins.host'),
        'buildStatus',
        "icon?style=plastic&subject={$buildNumber}&job={$ws}%2F{$appInfo->project_name}%2Fmaster"
    ]);
@endphp

<img class="shadow" alt="..." src="{{ $url }}" height="22" />
