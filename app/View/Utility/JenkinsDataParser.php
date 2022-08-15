<?php

namespace App\View\Utility;

use Illuminate\Support\Str;

class JenkinsDataParser
{
    private $projectName;
    private $jenkinsData;

    public function __construct($projectName, mixed $jenkinsData)
    {
        $this->projectName = $projectName;
        $this->jenkinsData = $jenkinsData;
    }

    public function IsDataNull() : bool
    {
        return $this->jenkinsData == null;
    }

    public function GetJobPlatform()
    {
        if (!isset($this->jenkinsData->build_platform)) { return ''; }

        $iconType = ($this->jenkinsData->build_platform == 'GooglePlay') ? 'fa fa-google' : 'fa fa-apple';
        return '<i class="pull-right ' . $iconType . ' aria-hidden="true"></i>';
    }

    public function GetStage()
    {
        if (!isset($this->jenkinsData->status))
        {
            return '<span class="text-secondary font-weight-bold">
                        <i class="fa fa-bell" aria-hidden="true"></i> NO BUILD
                    </span>';
        }

        return match($this->jenkinsData->status)
        {
            'SUCCESS' => '<span class="text-success font-weight-bold"><i class="fa fa-check-circle-o" aria-hidden="true"></i> SUCCESS</span>',
            'ABORTED' => '<span class="text-secondary font-weight-bold">STAGE: ' . Str::limit($this->jenkinsData->stop_details->stage, 14) . '</span>',
            'FAILED' => '<span class="text-danger font-weight-bold">STAGE: ' . Str::limit($this->jenkinsData->stop_details->stage, 14) . '</span>',
            'IN_PROGRESS' => '<span class="text-primary font-weight-bold">STAGE: ' . Str::limit($this->jenkinsData->stop_details->stage, 14) . '</span>',
            'NOT_EXECUTED' => '<span class="text-secondary font-weight-bold">NOT EXECUTED</span>',
            default => 'NOT_IMPLEMENTED'
        };
    }

    public function GetStageDetail()
    {
        if (!isset($this->jenkinsData->stop_details)) { return ''; }
        if (empty($this->jenkinsData->stop_details->output)) { return ''; }

        $detail = Str::limit($this->jenkinsData->stop_details->output, 29);

        return "<span class='badge bg-warning text-white'>
                    <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>
                </span>
                {$detail}
                <hr class='my-2'>";
    }

    public function GetJobEstimatedFinish()
    {
        if ($this->jenkinsData?->status != 'IN_PROGRESS') { return ''; }
        if (!isset($this->jenkinsData->estimated_time)) { return ''; }

        return 'Average Finish: <span class="text-primary font-weight-bold">' . $this->jenkinsData->estimated_time . "</span><hr class='my-2'>";
    }

    public function GetCommits()
    {
        $buildCommits = collect($this->jenkinsData?->change_sets ?? []);
        if (count($buildCommits) == 0) { return 'No Commit'; }

        $prettyCommits = collect();

        // add pretty commit history to build details view.
        $buildCommits->each(function ($commitText, $order) use (&$prettyCommits) {
            $prefix = ($order + 1) . '. ';
            $prettyText = Str::of(Str::limit(trim($commitText), 27))->newLine();
            $prettyCommits->push($prefix . nl2br($prettyText));
        });

        return $prettyCommits->implode(',');
    }

    public function GetJobBuildStatusImage()
    {
        $ws = config('jenkins.ws');
        $url = config('jenkins.host') . "/buildStatus/icon?subject={$this->jenkinsData?->id}&job={$ws}%2F{$this->projectName}%2Fmaster";
        return '<img alt="..." src="'.$url.'">';
    }
}
