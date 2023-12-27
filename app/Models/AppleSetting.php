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

class AppleSetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;

    use UsesCipherSweet;
    use UsesCipherSweetOptionalConfigs;

    protected $fillable = [
        'usermail',
        'app_specific_pass',
    ];

    protected $hidden = [
        'app_specific_pass',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static array $encryptedColumns = [
        'usermail',
        'app_specific_pass',
    ];

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }
}
