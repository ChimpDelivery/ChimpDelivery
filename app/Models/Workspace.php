<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;

class Workspace extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;

    // new users associated with that workspace
    public const DEFAULT_WS_ID = 1;

    // internal talus workspace
    public const INTERNAL_WS_ID = 2;

    protected $fillable = [
        'name',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'AppStoreConnectSetting',
        'AppStoreConnectSign',
        'AppleSetting',
        'GithubSetting',
    ];

    protected $casts = [

    ];

    protected $with = [
        'AppStoreConnectSetting',
        'AppStoreConnectSign',
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

    public function appStoreConnectSetting()
    {
        return $this->hasOne(AppStoreConnectSetting::class);
    }

    public function appleSetting()
    {
        return $this->hasOne(AppleSetting::class);
    }

    public function githubSetting()
    {
        return $this->hasOne(GithubSetting::class);
    }

    public function appStoreConnectSign()
    {
        return $this->hasOne(AppStoreConnectSign::class);
    }

    public function createApiToken()
    {
        $this->tokens()->delete();
        return $this->createToken('api-key')->plainTextToken;
    }

    public function createInviteCode() : string
    {
        $code = Str::random(10);

        $this->inviteCodes()->create([
            'code' => Str::random(10)
        ]);

        return $code;
    }
}
