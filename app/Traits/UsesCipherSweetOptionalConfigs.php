<?php

namespace App\Traits;

use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\EncryptedRow;

trait UsesCipherSweetOptionalConfigs
{
    public static function configureCipherSweet(EncryptedRow $encryptedRow) : void
    {
        foreach (self::$encryptedColumns as $column)
        {
            $encryptedRow
                ->addOptionalTextField($column)
                ->addBlindIndex($column, new BlindIndex($column));
        }
    }
}
