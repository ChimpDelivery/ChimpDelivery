<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'api_key',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [

    ];

    protected $with = [
        'AppStoreConnectSetting',
        'AppleSetting',
        'GithubSetting',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function apps()
    {
        return $this->hasMany(AppInfo::class);
    }

    public function inviteCodes()
    {
        return $this->hasMany(WorkspaceInviteCode::class);
    }

    public function AppStoreConnectSetting()
    {
        return $this->hasOne(AppStoreConnectSetting::class);
    }

    public function AppleSetting()
    {
        return $this->hasOne(AppleSetting::class);
    }

    public function GithubSetting()
    {
        return $this->hasOne(GithubSetting::class);
    }
}
