<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppInfo extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'app_icon',
        'app_icon_hash',
        'app_name',
        'app_bundle',
        'appstore_id',
        'fb_app_id',
        'elephant_id',
        'elephant_secret'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];
}
