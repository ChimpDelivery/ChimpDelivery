<?php

namespace App\Actions\Api\Jenkins;

enum JobStatus : string
{
    case NOT_EXECUTED = 'NOT_EXECUTED';
    case IN_PROGRESS = 'IN_PROGRESS';
    case ABORTED = 'ABORTED';
    case FAILED = 'FAILED';
    case SUCCESS = 'SUCCESS';

    case QUEUED = 'QUEUED';

    case NOT_IMPLEMENTED = 'NOT_IMPLEMENTED';

    public function PrettyName() : string
    {
        return match($this)
        {
            self::NOT_EXECUTED => 'NOT EXECUTED',
            self::IN_PROGRESS => 'IN PROGRESS',
            self::QUEUED => 'In Queue...',
            default => $this->value,
        };
    }

    public static function GetErrorStages() : array
    {
        return [
            self::ABORTED->value,
            self::FAILED->value,
        ];
    }
}
