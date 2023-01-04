<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// cipher sweet ns
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;

use App\Traits\UsesCipherSweetConfigs;

class WorkspaceInviteCode extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;
    use UsesCipherSweet;
    use UsesCipherSweetConfigs;

    protected $fillable = [
        'code',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected static array $encryptedColumns = [
        'code',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
