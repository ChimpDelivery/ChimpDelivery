<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// cipher sweet ns
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;

use App\Traits\UsesCipherSweetConfigs;

class AppleSetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;
    use UsesCipherSweet;
    use UsesCipherSweetConfigs;

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

    protected static array $encryptedColumns = [
        'app_specific_pass',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
