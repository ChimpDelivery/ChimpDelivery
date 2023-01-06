<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ParagonIE\ConstantTime\Hex;

class ReEncryptCipherSweetKey extends Command
{
    protected $signature = 'security:rotate_key';
    protected $description = 'Rotates CipherKey and Re-Encrypt Models';

    protected array $encryptedModels = [
        'App\Models\AppleSetting',
        'App\Models\AppStoreConnectSetting',
        'App\Models\GithubSetting',
        'App\Models\WorkspaceInviteCode',
    ];

    public function handle() : int
    {
        $encryptionKey = Hex::encode(random_bytes(32));

        $this->info("Key rotation is working...");
        $this->info("New Key: {$encryptionKey}");

        foreach ($this->encryptedModels as $model)
        {
            $this->call('ciphersweet:encrypt', [
                'model' => $model,
                'newKey' => $encryptionKey
            ]);
        }

        return Command::SUCCESS;
    }
}
