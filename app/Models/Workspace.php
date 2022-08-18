<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Workspace extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $guarded = [

    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'appstore_private_key',
        'appstore_issuer_id',
        'appstore_kid',
        'apple_usermail',
        'apple_app_pass',
        'github_org_name',
        'github_access_token',
        'github_template',
        'github_topic'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

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
}
