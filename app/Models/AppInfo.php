<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppInfo extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'app_icon',
        'app_name',
        'project_name',
        'app_bundle',
        'appstore_id',
        'fb_app_id',
        'fb_client_token',
        'ga_id',
        'ga_secret',
    ];

    protected $hidden = [
        'workspace',
        'created_at',
        'updated_at',
        'deleted_at',
        'git_url',
        'icon_name',
    ];

    protected $casts = [
    ];

    protected $appends = [
        'git_url',
    ];

    protected $with = [
        'workspace',
    ];

    protected function gitUrl() : Attribute
    {
        return new Attribute(fn () => implode('/', [
            'https://github.com',
            $this->workspace->githubSetting->organization_name,
            $this->project_name,
        ]));
    }

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
