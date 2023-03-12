<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Support\Facades\Auth;

use App\Actions\Api\S3\Provision\GetProvisionProfile;

class AppStoreConnectSign extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cert',
        'provision_profile',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
        'cert_name',
        'provision_name',
        'provision_expire',
    ];

    protected $casts = [
    ];

    protected $appends = [
        // file references stored as a path in db, lets make pretty
        'cert_name',
        'provision_name',

        //
        'provision_expire',
    ];

    protected function certName() : Attribute
    {
        return new Attribute(fn() => str($this->cert)->explode('/')->last() ?: 'Choose...');
    }

    protected function provisionName() : Attribute
    {
        return new Attribute(fn() => str($this->provision_profile)->explode('/')->last());
    }

    protected function provisionExpire() : Attribute
    {
        return new Attribute(function() {
            $provisionExpire = GetProvisionProfile::run(Auth::user(), $this->workspace)
                ->headers
                ->get(config('appstore-sign.provision.required_tags')['expire']['web']);

            return !empty($provisionExpire)
                ? "Expire Date: {$provisionExpire}"
                : 'Choose...';
        });
    }

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
