<?php

namespace App\View\Parsers;

use App\Actions\Api\Jenkins\JobPlatform;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

use App\Models\AppInfo;
use App\Actions\Api\Jenkins\JobStatus;

class JenkinsDataParser
{
    // text limits
    public const STOP_STAGE_LENGTH = 14;
    public const STOP_MSG_LENGTH = 29;
    public const COMMIT_LENGTH = 27;
    public const COMMIT_HASH_LENGTH = 7;

    private AppInfo $app;
    private mixed $jenkinsData;

    public function __construct(AppInfo $app)
    {
        $this->app = $app;
        $this->jenkinsData = $app->jenkins_data;
    }

    public function GetButtonImage()
    {
        $ws = Auth::user()->workspace->githubSetting->organization_name;
        $url = config('jenkins.host') . "/buildStatus/icon?subject={$this->jenkinsData?->id}&job={$ws}%2F{$this->app->project_name}%2Fmaster";
        return '<img alt="..." src="'.$url.'">';
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
        $buttonData .= $this->GetCommits();

        return [
            'header' => $buttonTitle,
            'body' => $buttonData
        ];
    }

    private function GetLogLink()
    {
        return "<a class='pull-right text-secondary'
                    data-toggle='tooltip'
                    data-placement='top'
                    title='Build Log'
                    href=/dashboard/build-log?id={$this->app->id}>
                        <i class='fa fa-external-link' aria-hidden='true'></i>
                </a>";
    }

    private function GetJobPlatform()
    {
        if (!isset($this->jenkinsData->build_platform)) { return ''; }

        $iconType = ($this->jenkinsData->build_platform == JobPlatform::GooglePlay->value)
            ? 'fa fa-google'
            : 'fa fa-apple';

        return '<i class="pull-right ' . $iconType . ' aria-hidden="true"></i>';
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
        return match($this->jenkinsData->status)
        {
            JobStatus::SUCCESS->value =>
                '<span class="text-success font-weight-bold">
                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                    SUCCESS
                </span>',

            JobStatus::ABORTED->value =>
                '<span class="text-secondary font-weight-bold">STAGE: ' . $stageName . '</span>',

            JobStatus::FAILED->value =>
                '<span class="text-danger font-weight-bold">STAGE: ' . $stageName . '</span>',

            JobStatus::IN_PROGRESS->value =>
                '<span class="text-primary font-weight-bold">STAGE: ' . $stageName . '</span>',

            JobStatus::NOT_EXECUTED->value =>
                '<span class="text-secondary font-weight-bold">NOT EXECUTED</span>',

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

    private function GetCommits()
    {
        $buildCommits = collect($this->jenkinsData?->change_sets ?? []);
        if (count($buildCommits) == 0) { return 'No Commit'; }

        $prettyCommits = collect();

        // add pretty commit history to build details view.
        $buildCommits->each(function ($commit, $order) use (&$prettyCommits)
        {
            $prettyText = Str::of(Str::limit(trim($commit->msg), self::COMMIT_LENGTH))->newLine();

            $orgName = Auth::user()->workspace->githubSetting->organization_name;
            $commitUrl = "https://github.com/{$orgName}/{$this->app->project_name}/commit/{$commit->id}";

            $prettyCommitMsg = "<span class='badge alert-primary'>"
                . Str::substr($commit->id, 0, self::COMMIT_HASH_LENGTH)
                . '</span> ' . nl2br($prettyText);

            $commitLink = "<a href='{$commitUrl}' target='_blank'>{$prettyCommitMsg}</a>";

            $prettyCommits->push($commitLink);
        });

        return $prettyCommits->implode('');
    }
}
