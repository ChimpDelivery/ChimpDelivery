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

class AppStoreConnectSetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;
    use UsesCipherSweet;

    protected $fillable = [
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

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public static function configureCipherSweet(EncryptedRow $encryptedRow) : void
    {
        $encryptedRow
            ->addOptionalTextField('private_key')
            ->addBlindIndex('private_key', new BlindIndex('private_key'));

        $encryptedRow
            ->addOptionalTextField('issuer_id')
            ->addBlindIndex('issuer_id', new BlindIndex('issuer_id'));

        $encryptedRow
            ->addOptionalTextField('kid')
            ->addBlindIndex('kid', new BlindIndex('kid'));
    }
}
