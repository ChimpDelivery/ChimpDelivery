<?php

namespace App\Models;

use App\Http\Traits\ClearsResponseCache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppInfo extends Model
{
    use SoftDeletes;
    use HasFactory;
    use ClearsResponseCache;

    public $guarded = [

    ];

    protected $fillable = [
        'workspace_id',
        'app_icon',
        'app_icon_hash',
        'app_name',
        'project_name',
        'app_bundle',
        'appstore_id',
        'fb_app_id',
        'ga_id',
        'ga_secret'
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

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
