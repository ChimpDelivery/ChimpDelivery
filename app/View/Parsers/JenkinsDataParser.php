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

    private mixed $lastBuild;

    public function __construct(private readonly AppInfo $app)
    {
        $this->lastBuild = $app->jenkins_data;
    }

    public function GetButtonData()
    {
        // prepare button header(title)
        $buttonTitle = $this->GetStage();

        // there is no build
        if (!$this->lastBuild)
        {
            return [
                'header' => $buttonTitle,
                'body' => 'First build not executed!',
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
            'body' => $buttonData,
        ];
    }

    private function GetLogLink() : View
    {
        return view('layouts.jenkins.job-log-button', [ 'appId' => $this->app->id ]);
    }

    private function GetJobPlatform() : View
    {
        $icon = JobPlatform::tryFrom($this->lastBuild->build_platform)->GetPlatformIcon();

        return view('layouts.jenkins.job-platform', [ 'icon' => $icon ]);
    }

    private function GetStage()
    {
        if (!isset($this->lastBuild->status))
        {
            return '<span class="text-secondary font-weight-bold">
                <i class="fa fa-bell" aria-hidden="true"></i> NO BUILD
            </span>';
        }

        // todo: failing at prepare stage - text color
        $stageName = Str::limit($this->lastBuild->stop_details->stage, $this->limits['stop_stage_length']);
        $prettyName = JobStatus::tryFrom($this->lastBuild->status)->PrettyName();

        return match(JobStatus::tryFrom($this->lastBuild->status))
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

    private function GetStageDetail() : View
    {
        return view('layouts.jenkins.info.job-stage-detail', [
            'last_build' => $this->lastBuild,
            'text_limits' => $this->limits,
        ]);
    }

    private function GetJobEstimatedFinish() : View
    {
        return view('layouts.jenkins.info.job-average-finish', [
            'last_build' => $this->lastBuild,
        ]);
    }

    private function GetCommit() : View
    {
        return view('layouts.jenkins.job-commit', [
            'app_commit' => $this->lastBuild?->commit,
            'limits' => $this->limits,
        ]);
    }
}
