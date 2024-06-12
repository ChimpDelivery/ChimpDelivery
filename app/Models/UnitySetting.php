<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Traits\UsesCipherSweetOptionalConfigs;

// cipher sweet ns
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;

class UnitySetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;

    use UsesCipherSweet;
    use UsesCipherSweetOptionalConfigs;

    protected $fillable = [
        'serial',
        'username',
        'password',
    ];

    protected $hidden = [
        'serial',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static array $encryptedColumns = [
        'serial',
        'password',
    ];

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
