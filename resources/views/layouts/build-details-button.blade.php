@php
    $currentBuildStatus = $appInfo?->build_status?->status;
    $currentBuildNumber = $appInfo?->build_number ?? '-';

    $title = ($currentBuildNumber == '-')
        ? "NO_BUILD"
        : "";
@endphp

@php
    $commitCount = count($appInfo?->change_sets ?? []);
    $isHrActive = $commitCount >= 0 ? '<hr class="my-2">' : '';
    $buildDetails = '';

    switch ($currentBuildStatus)
    {
        case 'IN_PROGRESS':
            $title = 'Current Stage: <span class="text-primary font-weight-bold">' . Str::limit($appInfo->build_stage, 15) . '</span>';
            $buildDetails .= 'Average Finish: <span class="text-primary font-weight-bold">' . $appInfo->estimated_time . "</span>{$isHrActive}";
        break;
        case 'FAILED':
            $title = '<span class="text-danger">
                        <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                        Failed: ' . $appInfo?->build_status?->message .
                    '</span>';
        break;
        case 'ABORTED':
            $title = '<span class="text-secondary"><i class="fa fa-stop-circle" aria-hidden="true"></i> Aborted: ' . $appInfo?->build_status?->message . '</span>';
        break;
        case 'SUCCESS':
            $title = '<span class="text-success"><i class="fa fa-check-circle-o" aria-hidden="true"></i> Success</span>';
        break;
    }

    // build details...
    for ($i = 0; $i < $commitCount; ++$i) { $buildDetails .= ($i + 1) . '. ' . nl2br(trim(Str::limit($appInfo?->change_sets[$i], 27)) . "\r\n"); }
    if ($commitCount == 0) { $buildDetails .= "No commit"; }
@endphp

<div class="container">
    <a tabindex="0" class="btn btn-sm" role="button" data-trigger="focus" data-toggle="popover" data-html="true" data-placement="bottom"
        title="{{ $title }}"
        data-content="{{ $buildDetails }}">
        <img alt="..." src="{{ config('jenkins.host') }}/buildStatus/icon?subject={{ $currentBuildNumber }}&job={{ config('jenkins.ws') }}%2F{{ $appInfo->project_name }}%2Fmaster">
    </a>
</div>
