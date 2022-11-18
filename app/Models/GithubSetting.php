<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GithubSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'personal_access_token',
        'organization_name',
        'template_name',
        'topic_name',
        'public_repo',
        'private_repo',
    ];

    protected $hidden = [
        'personal_access_token',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'personal_access_token' => 'encrypted',
        'public_repo' => 'boolean',
        'private_repo' => 'boolean',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
