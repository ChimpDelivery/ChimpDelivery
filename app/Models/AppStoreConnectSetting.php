<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppStoreConnectSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'private_key',
        'issuer_id',
        'kid',
    ];

    protected $hidden = [
        'private_key',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'private_key' => 'encrypted',
        'issuer_id' => 'encrypted',
        'kid' => 'encrypted',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
