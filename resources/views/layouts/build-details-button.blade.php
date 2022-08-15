@php
    use App\View\Utility\AppInfoView;

    $jenkinsData = $appInfo->jenkins_data;

    $buttonTitle = AppInfoView::GetStage($jenkinsData);
    $buttonData = '';

    if ($jenkinsData != null)
    {
        $buttonTitle .= AppInfoView::GetJobPlatform($jenkinsData);
        $buttonData .= AppInfoView::GetStageDetail($jenkinsData);

        if ($jenkinsData?->status == 'IN_PROGRESS')
        {
            $buttonData .= AppInfoView::GetJobEstimatedFinish($jenkinsData);
        }

        $buttonData .= AppInfoView::GetCommits($jenkinsData);
    }
@endphp

<div class="container">
    <a tabindex="0" class="btn btn-sm" role="button" data-trigger="focus" data-toggle="popover" data-html="true" data-placement="bottom"
        title="{{ $buttonTitle }}"
        data-content="{{ $buttonData }}">
        <img alt="..." src="{{ config('jenkins.host') }}/buildStatus/icon?subject={{ $jenkinsData?->id }}&job={{ config('jenkins.ws') }}%2F{{ $appInfo->project_name }}%2Fmaster">
    </a>
</div>
