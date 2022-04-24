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
    $commitHistory = $appInfo->build_status == 'BUILDING' ? '<span class=text-primary>Estimated finish: ' . $appInfo->estimated_time . '</span>' : '';

    for ($i = 0; $i < $commitCount; ++$i)
    {
        $commitHistory .= ($i + 1) . '. ' . nl2br(trim($appInfo->change_sets[$i]) . "\r\n");
    }
@endphp

<div>
    <button type="button"
            class="btn btn-sm {{ $backgroundColor }}"
            data-toggle="popover"
            title="Build: {{ $appInfo->build_number }}"
            data-html="true"
            data-content="{{ $commitHistory }}">
        {{ $appInfo->build_status }}
    </button>
</div>
