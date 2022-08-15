<?php

namespace App\Http\Services;

use Illuminate\Support\Str;

class AppInfoView
{
    public static function GetJobPlatform($buildPlatform)
    {
        $iconType = ($buildPlatform == 'GooglePlay') ? 'fa fa-google' : 'fa fa-apple';

        return '<i class="pull-right ' . $iconType . ' aria-hidden="true"></i>';
    }

    public static function GetStopStage($jenkinsData)
    {
        if (is_null($jenkinsData))
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
            default => 'NOT_IMPLEMENTED'
        };
    }

    public static function GetStopStageDetail($stopDetails)
    {
        if (!isset($stopDetails)) { return ''; }
        if (empty($stopDetails->output)) { return ''; }

        $detail = Str::limit($stopDetails->output, 29);

        return "<span class='badge bg-warning text-white'>
                    <i class='fa fa-exclamation-triangle' aria-hidden='true'></i>
                </span>
                {$detail}
                <hr class='my-2'>";
    }

    public static function GetJobEstimatedFinish($estimatedTime)
    {
        return 'Average Finish: <span class="text-primary font-weight-bold">' . $estimatedTime . "</span><hr class='my-2'>";
    }

    public static function GetNoBuildButton()
    {
        return '<span class="text-secondary"><i class="fa fa-bell" aria-hidden="true"></i> No Build</span>';
    }

    public static function SetCommitsView($buildCommits, &$buttonData)
    {
        if (count($buildCommits) == 0)
        {
            $buttonData .= 'No commit';
            return;
        }

        // add pretty commit history to build details view.
        $buildCommits->each(function ($commitText, $order) use (&$buttonData) {
            $prefix = ($order + 1) . '. ';
            $prettyText = Str::of(Str::limit(trim($commitText), 27))->newLine();
            $buttonData .= $prefix . nl2br($prettyText);
        });
    }
}
