@php
    $backgroundColor = match ($appInfo->build_status)
    {
        'BUILDING' => 'btn-primary font-weight-bold',
        'SUCCESS' => 'btn-success font-weight-bold',
        'FAILURE' => 'btn-danger font-weight-bold',
        default => 'btn-secondary font-weight-bold'
    };
@endphp

@php
    $commitCount = count($appInfo?->change_sets);
    $commitHistory = $appInfo->build_status == 'BUILDING' ? '<span class=text-primary>Estimated finish: ' . $appInfo->estimated_time . '</span><br />' : '';

    for ($i = 0; $i < $commitCount; ++$i)
    {
        $commitHistory .= ($i + 1) . '. ' . nl2br(trim($appInfo->change_sets[$i]) . "\r\n");
    }
@endphp

<div class="container">
    <a tabindex="0"
        class="btn btn-sm {{ $backgroundColor }}"
        role="button"
        title="Build Number: {{ $appInfo->build_number }}"
        data-trigger="focus"
        data-toggle="popover"
        data-html="true"
        data-placement="bottom"
        data-content="{{ $commitHistory }}">
        {{ $appInfo->build_status }}
    </a>
</div>
