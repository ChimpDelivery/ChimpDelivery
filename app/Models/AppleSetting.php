<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppleSetting extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'usermail',
        'app_specific_pass',
    ];

    protected $hidden = [
        'app_specific_pass',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'app_specific_pass' => 'encrypted'
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
