@php
    // build related
    $buildStatus = $appInfo?->build_status?->status;
    $buildNumber = $appInfo?->build_number;
    $buildCommits = collect($appInfo?->change_sets ?? []);

    // fail/abort messages
    $buildStopStage = $appInfo?->build_status?->message ?? '';

    // jenkins prepare stage invoked!
    // /wfapi/runs return failing response but actually aborted
    if ($buildStopStage == 'Prepare')
    {
        $buildStatus = 'ABORTED';
    }

    $buildStopStageDetail = $appInfo?->build_status?->message_detail ?? '';

    $buttonTitle = $buildNumber ?? '<span class="text-secondary"><i class="fa fa-bell" aria-hidden="true"></i> No Build</span>';
    $buttonData = '';

    switch ($buildStatus)
    {
        case 'IN_PROGRESS':
            $buttonTitle = 'Current Stage: <span class="text-primary font-weight-bold">' . Str::limit($appInfo?->build_stage, 15) . '</span>';
            $buttonData .= 'Average Finish: <span class="text-primary font-weight-bold">' . $appInfo?->estimated_time . "</span><hr class='my-2'>";
        break;

        case 'FAILED':
            $buttonTitle = '<span class="text-danger">
                            <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                            Failed: ' . $buildStopStage .
                        '</span>';
        break;

        case 'ABORTED':
            $buttonTitle = '<span class="text-secondary">
                            <i class="fa fa-stop-circle" aria-hidden="true"></i>
                            Aborted: ' . $buildStopStage.
                        '</span>';
        break;

        case 'SUCCESS':
            $buttonTitle = '<span class="text-success">
                            <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                            Success
                        </span>';
        break;
    }

    // add pretty commit history to build details view.
    $buildCommits->each(function ($commitText, $order) use (&$buttonData) {
        $prefix = ($order + 1) . '. ';
        $prettyText = Str::of(Str::limit(trim($commitText), 27))->newLine();
        $buttonData .= $prefix . nl2br($prettyText);
    });

    if (count($buildCommits) == 0)
    {
        $buttonData .= "No commit";
    }
@endphp

<div class="container">
    <a tabindex="0" class="btn btn-sm" role="button" data-trigger="focus" data-toggle="popover" data-html="true" data-placement="bottom"
        title="{{ $buttonTitle }}"
        data-content="{{ $buttonData }}">
        <img alt="..." src="{{ config('jenkins.host') }}/buildStatus/icon?subject={{ $buildNumber }}&job={{ config('jenkins.ws') }}%2F{{ $appInfo->project_name }}%2Fmaster">
    </a>
</div>
