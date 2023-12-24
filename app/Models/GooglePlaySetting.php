<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

// cipher sweet ns
use Spatie\LaravelCipherSweet\Contracts\CipherSweetEncrypted;
use Spatie\LaravelCipherSweet\Concerns\UsesCipherSweet;
use ParagonIE\CipherSweet\EncryptedRow;
use ParagonIE\CipherSweet\BlindIndex;

class GooglePlaySetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;
    use UsesCipherSweet;

    protected $fillable = [
        'keystore_file',
        'keystore_pass',
        'service_account',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public static function configureCipherSweet(EncryptedRow $encryptedRow) : void
    {
        $encryptedRow
            ->addOptionalTextField('keystore_pass')
            ->addBlindIndex('keystore_pass', new BlindIndex('keystore_pass'));
    }
}
