<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

use App\Notifications\Auth\VerifyEmailQueued;
use App\Notifications\Auth\ResetPasswordQueued;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasApiTokens;

    protected $fillable = [
        'workspace_id',
        'name',
        'email',
        'password',
    ];

    protected $guarded = [
        'workspace_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'permissions',
        'gravatar',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'permissions',
    ];

    protected $appends = [
        'gravatar',
    ];

    /**
     * @return BelongsTo<Workspace, User>
     */
    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function orgName() : ?string
    {
        return $this->workspace->githubSetting->organization_name;
    }

    public function isNew() : bool
    {
        return $this->workspace_id === config('workspaces.default_ws_id');
    }

    public function isInternal() : bool
    {
        return $this->workspace_id === config('workspaces.internal_ws_id');
    }

    public function createApiToken(string $tokenName) : string
    {
        if (!$this->isNew() && $this->can('create api token'))
        {
            // Extend: Users can only have 1 api token...
            $this->tokens()->where('name', '=', $tokenName)->delete();
            return $this->createToken($tokenName)->plainTextToken;
        }

        return '';
    }

    // api reference: https://en.gravatar.com/site/implement/images/php/
    protected function gravatar() : Attribute
    {
        return new Attribute(function () {
            $hash = md5(strtolower(trim($this->attributes['email'])));
            return "https://www.gravatar.com/avatar/{$hash}";
        });
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmailQueued);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordQueued($token));
    }
}
