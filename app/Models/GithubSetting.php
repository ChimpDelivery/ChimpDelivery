<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

// cipher sweet ns
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;

use App\Traits\UsesCipherSweetConfigs;

class GithubSetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;
    use UsesCipherSweet;
    use UsesCipherSweetConfigs;

    protected $fillable = [
        'personal_access_token',
        'organization_name',
        'template_name',
        'topic_name',
        'public_repo',
        'private_repo',
    ];

    protected $hidden = [
        'personal_access_token',
        'public_repo',
        'private_repo',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'public_repo' => 'boolean',
        'private_repo' => 'boolean',
    ];

    protected static array $encryptedColumns = [
        'personal_access_token',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class);
    }
}
