<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ParagonIE\ConstantTime\Hex;

class ReEncryptCipherSweetKey extends Command
{
    protected $signature = 'dashboard:rotate-key';
    protected $description = 'Create new Cipher Key and Re-Encrypt related Models';

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
