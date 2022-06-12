@php
    $currentBuildStatus = $appInfo?->build_status?->status;

	$title = "Build Number: <span class='text-dark font-weight-bold'>{$appInfo?->build_number}</span>";
@endphp

@php
    $commitCount = count($appInfo?->change_sets ?? []);
    $isHrActive = $commitCount >= 0 ? '<hr class="my-2">' : '';
    $buildDetails = '';

    switch ($currentBuildStatus)
    {
        case 'IN_PROGRESS':
            $buildDetails .= 'Current Stage: <span class="text-success font-weight-bold">' . Str::limit($appInfo->build_stage, 18) . '</span><hr class="my-2">';
            $buildDetails .= 'Average Finish: <span class="text-primary font-weight-bold">' . $appInfo->estimated_time . "</span>{$isHrActive}";
        break;
    }

    // build details...
    for ($i = 0; $i < $commitCount; ++$i) { $buildDetails .= ($i + 1) . '. ' . nl2br(trim($appInfo?->change_sets[$i]) . "\r\n"); }
    if ($commitCount == 0) { $buildDetails .= "No commit"; }
@endphp

<div class="container">
    <a tabindex="0"
        class="btn btn-sm"
        role="button"
        title="{{ $title }}"
        data-trigger="focus"
        data-toggle="popover"
        data-html="true"
        data-placement="bottom"
        data-content="{{ $buildDetails }}">
        <img alt="..." src="https://jenkins.talusstudio.eu.ngrok.io/buildStatus/icon?job={{ config('jenkins.ws') }}%2F{{ $appInfo->project_name }}%2Fmaster">
    </a>
</div>
