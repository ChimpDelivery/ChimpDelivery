<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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

    public function users() : HasMany
    {
        return $this->hasMany(User::class);
    }

    public function apps() : HasMany
    {
        return $this->hasMany(AppInfo::class);
    }

    public function inviteCodes() : HasMany
    {
        return $this->hasMany(WorkspaceInviteCode::class);
    }

    public function appStoreConnectSetting() : HasOne
    {
        return $this->hasOne(AppStoreConnectSetting::class)->withDefault([
            'private_key' => 'Choose...',
        ]);
    }

    public function appleSetting() : HasOne
    {
        return $this->hasOne(AppleSetting::class)->withDefault();
    }

    public function githubSetting() : HasOne
    {
        return $this->hasOne(GithubSetting::class)->withDefault([
            'public_repo' => 0,
            'private_repo' => 0,
        ]);
    }

    public function googlePlaySetting() : HasOne
    {
        return $this->hasOne(GooglePlaySetting::class)->withDefault([
            'service_account' => 'Choose...',
            'keystore_file' => 'Choose...',
        ]);
    }

    public function githubOrgName() : null|string
    {
        return $this->githubSetting->organization_name;
    }

    public function appStoreConnectSign() : HasOne
    {
        return $this->hasOne(AppStoreConnectSign::class)->withDefault([
            'cert' => 'Choose...',
            'provision_profile' => 'Choose...',
        ]);
    }

    public function createInviteCode() : string
    {
        $code = str(Str::random(12))->upper()->toString();

        // Extend: Workspaces can only have 1 invite code...
        $this->inviteCodes()->delete();
        $this->inviteCodes()->create([ 'code' => $code ]);

        return $code;
    }
}
