@php
    use App\View\Utility\JenkinsDataParser;

    $parser = new JenkinsDataParser($appInfo->project_name, $appInfo->jenkins_data);

    $buttonTitle = $parser->GetStage();
    $buttonData = '';

    if (!$parser->IsDataNull())
    {
        $buttonTitle .= $parser->GetJobPlatform();
        $buttonData .= $parser->GetStageDetail();

        if ($appInfo?->jenkins_data?->status == 'IN_PROGRESS')
        {
            $buttonData .= $parser->GetJobEstimatedFinish();
        }

        $buttonData .= $parser->GetCommits();
    }
@endphp

<div class="container">
    <a tabindex="0" class="btn btn-sm" role="button" data-trigger="focus" data-toggle="popover" data-html="true" data-placement="bottom"
        title="{{ $buttonTitle }}"
        data-content="{{ $buttonData }}">
        {!! $parser->GetJobBuildStatusImage() !!}
    </a>
</div>
