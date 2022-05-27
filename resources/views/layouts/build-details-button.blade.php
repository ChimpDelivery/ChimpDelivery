@php
    $backgroundColor = match ($appInfo->build_status->status)
    {
        'IN_PROGRESS' => 'btn-primary font-weight-bold',
        'SUCCESS' => 'btn-success font-weight-bold',
        'FAILED' => 'btn-danger font-weight-bold',
        default => 'btn-secondary font-weight-bold'
    };

	$title = "Build Number: <span class='text-dark font-weight-bold'>{$appInfo->build_number}</span>";
@endphp

@php
	$commitCount = count($appInfo?->change_sets);
	$isHrActive = $commitCount >= 0 ? '<hr class="my-2">' : '';

	$buildDetails = '';
	if ($appInfo->build_status->status == 'IN_PROGRESS')
	{
		$buildDetails .= 'Current Stage: <span class="text-success font-weight-bold">' . $appInfo->build_stage . '</span><hr class="my-2">';
		$buildDetails .= 'Average Finish: <span class="text-primary font-weight-bold">' . $appInfo->estimated_time . "</span>{$isHrActive}";
	}

	if ($appInfo->build_status->status == 'FAILED')
	{
		$buildDetails .= 'Failed at: <span class="text-danger font-weight-bold">' . $appInfo->build_status->message . '</span><hr class="my-2">';
	}

    for ($i = 0; $i < $commitCount; ++$i)
    {
        $buildDetails .= ($i + 1) . '. ' . nl2br(trim($appInfo->change_sets[$i]) . "\r\n");
    }

	if ($commitCount == 0)
	{
		$buildDetails .= "No commit";
	}
@endphp

<div class="container">
    <a tabindex="0"
        class="btn btn-sm {{ $backgroundColor }}"
        role="button"
        title="{{ $title }}"
        data-trigger="focus"
        data-toggle="popover"
        data-html="true"
        data-placement="bottom"
        data-content="{{ $buildDetails }}">
        {{ $appInfo->build_status->status }}
    </a>
</div>
