<?php

namespace App\Http\Services;

use Illuminate\Support\Str;

class AppInfoView
{
    public static function GetJobPlatform(mixed $jenkinsData)
    {
        if (!isset($jenkinsData->platform)) { return ''; }

        $iconType = ($jenkinsData->platform == 'GooglePlay') ? 'fa fa-google' : 'fa fa-apple';
        return '<i class="pull-right ' . $iconType . ' aria-hidden="true"></i>';
    }

    public static function GetStage($jenkinsData)
    {
        if (!isset($jenkinsData->status))
        {
            return '<span class="text-secondary font-weight-bold">
                        <i class="fa fa-bell" aria-hidden="true"></i> NO BUILD
                    </span>';
        }

        return match($jenkinsData->status)
        {
            'SUCCESS' => '<span class="text-success font-weight-bold"><i class="fa fa-check-circle-o" aria-hidden="true"></i> SUCCESS</span>',
            'ABORTED' => '<span class="text-secondary font-weight-bold">STAGE: ' . Str::limit($jenkinsData->stop_details->stage, 14) . '</span>',
            'FAILED' => '<span class="text-danger font-weight-bold">STAGE: ' . Str::limit($jenkinsData->stop_details->stage, 14) . '</span>',
            'IN_PROGRESS' => '<span class="text-primary font-weight-bold">STAGE: ' . Str::limit($jenkinsData->stop_details->stage, 14) . '</span>',
            default => 'NOT_IMPLEMENTED'
        };
    }

    public static function GetStageDetail($jenkinsData)
    {
        if (!isset($jenkinsData->stop_details)) { return ''; }
        if (empty($jenkinsData->stop_details->output)) { return ''; }

        $detail = Str::limit($jenkinsData->stop_details->output, 29);

        return "<span class='badge bg-warning text-white'>
                    <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>
                </span>
                {$detail}
                <hr class='my-2'>";
    }

    public static function GetJobEstimatedFinish($jenkinsData)
    {
        if (!isset($jenkinsData->estimated_time)) { return ''; }

        return 'Average Finish: <span class="text-primary font-weight-bold">' . $jenkinsData->estimated_time . "</span><hr class='my-2'>";
    }

    public static function GetCommits($jenkinsData)
    {
        $buildCommits = collect($jenkinsData?->change_sets ?? []);
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
}
