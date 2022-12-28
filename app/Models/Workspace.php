<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Str;

use Laravel\Sanctum\HasApiTokens;

class Workspace extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasApiTokens;

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

    /**
     * @return HasMany<User>
     */
    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * @return HasMany<AppInfo>
     */
    public function apps() : HasMany
    {
        return $this->hasMany(AppInfo::class);
    }

    /**
     * @return HasMany<WorkspaceInviteCode>
     */
    public function inviteCodes() : HasMany
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
        $code = str(Str::random(12))->upper()->toString();

        $this->inviteCodes()->delete();
        $this->inviteCodes()->create([
            'code' => $code
        ]);

        return $code;
    }
}
