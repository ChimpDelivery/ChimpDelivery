<?php

namespace App\Traits;

use ParagonIE\CipherSweet\BlindIndex;
use ParagonIE\CipherSweet\EncryptedRow;

trait UsesCipherSweetConfigs
{
    public static function configureCipherSweet(EncryptedRow $encryptedRow) : void
    {
        foreach (self::$encryptedColumns as $column)
        {
            $encryptedRow
                ->addField($column)
                ->addBlindIndex($column, new BlindIndex($column));
        }
    }
}
