<?php

namespace App\View\Parsers;

use App\Actions\Api\Jenkins\JobPlatform;

use Illuminate\Support\Str;
use Illuminate\Contracts\View\View;

use App\Models\AppInfo;
use App\Actions\Api\Jenkins\JobStatus;

class JenkinsDataParser
{
    // text limits
    public array $limits = [
        'stop_stage_length' => 14,
        'stop_msg_length' => 29,
        'commit_length' => 20,
        'commit_hash_length' => 7,
    ];

    public const STOP_STAGE_LENGTH = 14;
    public const STOP_MSG_LENGTH = 29;

    private AppInfo $app;
    private mixed $jenkinsData;

    public function __construct(AppInfo $app)
    {
        $this->app = $app;
        $this->jenkinsData = $app->jenkins_data;
    }

    public function GetButtonData()
    {
        // prepare button header(title)
        $buttonTitle = $this->GetStage();

        // there is no build
        if ($this->jenkinsData == null)
        {
            return [
                'header' => $buttonTitle,
                'body' => 'First build not executed!'
            ];
        }

        $buttonTitle .= $this->GetJobPlatform();
        $buttonTitle .= $this->GetLogLink();

        // prepare button body
        $buttonData = $this->GetStageDetail();
        $buttonData .= $this->GetJobEstimatedFinish();
        $buttonData .= $this->GetCommit();

        return [
            'header' => $buttonTitle,
            'body' => $buttonData
        ];
    }

    private function GetLogLink() : View
    {
        return view('layouts.jenkins.job-log-button', [ 'appId' => $this->app->id ]);
    }

    private function GetJobPlatform() : View
    {
        $icon = JobPlatform::tryFrom($this->jenkinsData->build_platform)->GetPlatformIcon();

        return view('layouts.jenkins.job-platform', [ 'icon' => $icon ]);
    }

    private function GetStage()
    {
        if (!isset($this->jenkinsData->status))
        {
            return '<span class="text-secondary font-weight-bold">
                        <i class="fa fa-bell" aria-hidden="true"></i> NO BUILD
                    </span>';
        }

        // todo: failing at prepare stage - text color
        $stageName = Str::limit($this->jenkinsData->stop_details->stage, self::STOP_STAGE_LENGTH);
        $prettyName = JobStatus::tryFrom($this->jenkinsData->status)->PrettyName();

        return match(JobStatus::tryFrom($this->jenkinsData->status))
        {
            JobStatus::IN_QUEUE =>
                "<span class='alert-warning bg-transparent font-weight-bold'>
                    <i class='fa fa-clock-o' aria-hidden='true'></i>
                    {$prettyName}
                </span>",

            JobStatus::NOT_EXECUTED =>
                "<span class='text-secondary font-weight-bold'>
                    {$prettyName}
                </span>",

            JobStatus::SUCCESS =>
                "<span class='text-success font-weight-bold'>
                    <i class='fa fa-check-circle-o' aria-hidden='true'></i>
                    {$prettyName}
                </span>",

            JobStatus::ABORTED =>
                '<span class="text-secondary font-weight-bold">
                    STAGE: ' . $stageName .
                '</span>',

            JobStatus::FAILED =>
                '<span class="text-danger font-weight-bold">
                    STAGE: ' . $stageName .
                '</span>',

            JobStatus::IN_PROGRESS =>
                '<span class="text-primary font-weight-bold">
                    STAGE: ' . $stageName .
                '</span>',

            default => JobStatus::NOT_IMPLEMENTED->value
        };
    }

    private function GetStageDetail()
    {
        if (!isset($this->jenkinsData->stop_details)) { return ''; }
        if (empty($this->jenkinsData->stop_details->output)) { return ''; }

        $detail = Str::limit($this->jenkinsData->stop_details->output, self::STOP_MSG_LENGTH);

        return "<span class='badge bg-warning text-white'>
                    <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>
                </span>
                {$detail}
                <hr class='my-2'>";
    }

    private function GetJobEstimatedFinish()
    {
        if ($this->jenkinsData?->status != 'IN_PROGRESS') { return ''; }
        if (!isset($this->jenkinsData->estimated_time)) { return ''; }

        return 'Average Finish: <span class="text-primary font-weight-bold">' . $this->jenkinsData->estimated_time . "</span><hr class='my-2'>";
    }

    private function GetCommit() : View
    {
        return view('layouts.jenkins.job-commit', [
            'app_commit' => $this->jenkinsData?->commit,
            'limits' => $this->limits,
        ]);
    }
}
