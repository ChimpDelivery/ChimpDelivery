<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppStoreConnectSign extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'workspace_id',
        'cert',
        'provision_profile',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'cert_name',
        'provision_name',
    ];

    protected $casts = [
    ];

    protected $appends = [
        // file references stored as a path in db, lets make pretty
        'cert_name',
        'provision_name',
    ];

    protected function certName() : Attribute
    {
        return new Attribute(fn() => str($this->cert)->explode('/')->last());
    }

    protected function provisionName() : Attribute
    {
        return new Attribute(fn() => str($this->provision_profile)->explode('/')->last());
    }

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
