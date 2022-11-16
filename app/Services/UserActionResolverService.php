<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserActionResolverService
{
    public readonly ActionUserType $userType;
    public readonly int $workspaceId;
    public readonly bool $isAllowed;

    public function __construct(string $permission)
    {
        $isWebUser = Auth::guard('web')->check();
        if ($isWebUser)
        {
            $this->userType = ActionUserType::Web;
            $this->workspaceId = Auth::user()->workspace->id;
            $this->isAllowed = Auth::user()->can($permission);
            return;
        }

        $isApiUser = Auth::guard('workspace-api')->check();
        $this->userType = ActionUserType::Api;
        $this->workspaceId = Auth::user()->id;
        $this->isAllowed = $isApiUser;
    }
}
