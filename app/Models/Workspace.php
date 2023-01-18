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
    ];

    protected $casts = [

    ];

    protected $with = [

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
        return $this->hasOne(AppStoreConnectSetting::class)->withDefault([
            'private_key' => 'Choose...',
        ]);
    }

    public function appleSetting()
    {
        return $this->hasOne(AppleSetting::class)->withDefault();
    }

    public function githubSetting()
    {
        return $this->hasOne(GithubSetting::class)->withDefault([
            'public_repo' => 0,
            'private_repo' => 0,
        ]);
    }

    public function appStoreConnectSign()
    {
        return $this->hasOne(AppStoreConnectSign::class)->withDefault([
            'cert' => 'Choose...',
            'provision_profile' => 'Choose...',
        ]);
    }

    public function createApiToken()
    {
        $this->tokens()->delete();
        return $this->createToken('api-key')->plainTextToken;
    }

    public function createInviteCode() : string
    {
        $code = str(Str::random(12))->upper()->toString();

        // Extend: Workspaces can only have 1 invite code...
        $this->inviteCodes()->delete();
        $this->inviteCodes()->create([
            'code' => $code
        ]);

        return $code;
    }
}
