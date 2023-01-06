<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ParagonIE\ConstantTime\Hex;

use App\Models\AppleSetting;
use App\Models\AppStoreConnectSetting;
use App\Models\GithubSetting;
use App\Models\WorkspaceInviteCode;

class RotateCipherSweetKey extends Command
{
    protected $signature = 'dashboard:rotate-key';
    protected $description = 'Create new Cipher Key and Re-Encrypt related Models';

    protected array $encryptedModels = [
        AppleSetting::class,
        AppStoreConnectSetting::class,
        GithubSetting::class,
        WorkspaceInviteCode::class,
    ];

    public function handle() : int
    {
        // logic must be same in ciphersweet:generate-key command
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
