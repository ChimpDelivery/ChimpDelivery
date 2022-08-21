<?php

namespace App\Models;

use App\Http\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppInfo extends Model
{
    use HasFactory;
    use SoftDeletes;
    use ClearsResponseCache;

    protected $fillable = [
        'workspace_id',
        'app_icon',
        'app_name',
        'project_name',
        'app_bundle',
        'appstore_id',
        'fb_app_id',
        'ga_id',
        'ga_secret',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
