@php
    $parser = new App\View\Parsers\JenkinsDataParser($appInfo);
    $buttonData = $parser->GetButtonData();
@endphp

<div class="container">
    <a tabindex="0"
        class="btn btn-sm"
        role="button"
        data-trigger="focus"
        data-toggle="popover"
        data-html="true"
        data-placement="bottom"
        title="{{ $buttonData['header'] }}"
        data-content="{{ $buttonData['body'] }}">
        @include('layouts.jenkins.build-status-img')
    </a>
</div>
