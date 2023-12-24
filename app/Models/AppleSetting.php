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

class AppleSetting extends Model implements CipherSweetEncrypted
{
    use HasFactory;
    use SoftDeletes;
    use UsesCipherSweet;

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

    public function workspace() : BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public static function configureCipherSweet(EncryptedRow $encryptedRow) : void
    {
        $encryptedRow
            ->addOptionalTextField('usermail')
            ->addBlindIndex('usermail', new BlindIndex('usermail'));

        $encryptedRow
            ->addOptionalTextField('app_specific_pass')
            ->addBlindIndex('app_specific_pass', new BlindIndex('app_specific_pass'));
    }
}
