<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

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

    protected $hidden = [
        'password',
        'remember_token',
        'workspace',
        'gravatar',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'workspace',
    ];

    protected $appends = [
        'gravatar'
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }

    public function orgName() : string
    {
        return $this->workspace->githubSetting->organization_name;
    }

    public function isNew() : bool
    {
        return $this->workspace_id === Workspace::DEFAULT_WS_ID;
    }

    public function isInternal() : bool
    {
        return $this->workspace_id === Workspace::INTERNAL_WS_ID;
    }

    public function createApiToken() : string
    {
        $this->tokens()->delete();
        return $this->createToken('api-key')->plainTextToken;
    }

    protected function gravatar() : Attribute
    {
        return new Attribute(function () {
            $hash = md5(strtolower(trim($this->attributes['email'])));
            return "https://www.gravatar.com/avatar/{$hash}";
        });
    }
}
