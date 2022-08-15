@php
    use App\Http\Services\AppInfoView;

    $jenkinsData = $appInfo->jenkins_data;

    $buttonTitle = AppInfoView::GetStopStage($jenkinsData);
    $buttonData = '';

    if ($jenkinsData != null)
    {
        $buttonData = AppInfoView::GetStopStageDetail($jenkinsData?->stop_details);

        if ($jenkinsData?->status == 'IN_PROGRESS')
        {
            $buttonData .= AppInfoView::GetJobEstimatedTime($appInfo?->estimated_time);
        }

        AppInfoView::SetCommitsView(collect($jenkinsData?->change_sets ?? []), $buttonData);

        $buttonTitle .= AppInfoView::GetJobPlatform($jenkinsData?->build_platform);
    }
@endphp

<div class="container">
    <a tabindex="0" class="btn btn-sm" role="button" data-trigger="focus" data-toggle="popover" data-html="true" data-placement="bottom"
        title="{{ $buttonTitle }}"
        data-content="{{ $buttonData }}">
        <img alt="..." src="{{ config('jenkins.host') }}/buildStatus/icon?subject={{ $jenkinsData?->id }}&job={{ config('jenkins.ws') }}%2F{{ $appInfo->project_name }}%2Fmaster">
    </a>
</div>
