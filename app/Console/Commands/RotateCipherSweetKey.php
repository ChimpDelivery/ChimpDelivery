<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ParagonIE\ConstantTime\Hex;

use Illuminate\Support\Carbon;

use App\Models\AppleSetting;
use App\Models\AppStoreConnectSetting;
use App\Models\GithubSetting;
use App\Models\GooglePlaySetting;
use App\Models\WorkspaceInviteCode;

class RotateCipherSweetKey extends Command
{
    protected $signature = 'dashboard:rotate-key {--show : shows encryption keys}';
    protected $description = 'Rotates CipherSweet Encryption Key in application.';

    // must be synced with variable in .env file
    protected const CIPHERSWEET_KEY_NAME = 'CIPHERSWEET_KEY';

    protected array $encryptedModels = [
        AppleSetting::class,
        AppStoreConnectSetting::class,
        GithubSetting::class,
        GooglePlaySetting::class,
        WorkspaceInviteCode::class,
    ];

    public function handle() : int
    {
        // maintenance mode
        $this->call('down', [ '--secret' => config('app.down_secret') ]);
        $this->info(Carbon::now() . ' | Key Rotation is starting...');

        // key backups
        $oldKey = config('ciphersweet.providers.string.key');
        $newKey = $this->GenerateRandomKey();

        $this->info('CipherSweet Key rotation is working...');
        if ($this->option('show'))
        {
            $this->info("New CipherSweet Key: {$newKey}");
        }

        $this->EncryptModels($newKey);

        // revert back when failed
        if (!$this->WriteNewEnvironmentFileWith($newKey))
        {
            $this->EncryptModels($oldKey);
            $this->call('dashboard:up');

            return Command::FAILURE;
        }

        $this->laravel['config']['ciphersweet.providers.string.key'] = $newKey;

        $this->info('CipherSweet Encryption Key rotated successfully!');

        /// restarting horizon required
        $this->call('dashboard:restart-horizon');
        $this->call('dashboard:up');

        return Command::SUCCESS;
    }

    protected function EncryptModels(string $key) : void
    {
        // re-encrypt current models
        // after re-encryption, update .env key
        foreach ($this->encryptedModels as $model)
        {
            $this->info("Model: {$model}");
            $this->call('ciphersweet:encrypt', [
                'model' => $model,
                'newKey' => $key,
            ]);
        }
    }

    protected function GenerateRandomKey() : string
    {
        return Hex::encode(random_bytes(32));
    }

    protected function WriteNewEnvironmentFileWith($key) : bool
    {
        $replaced = preg_replace(
            pattern: $this->KeyReplacementPattern(),
            replacement: self::CIPHERSWEET_KEY_NAME . '=' . $key,
            subject: $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null)
        {
            $this->error("Unable to set application key. CipherSweet Key could not found in the .env file.");
            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    protected function KeyReplacementPattern() : string
    {
        $escaped = preg_quote('=' . $this->laravel['config']['ciphersweet.providers.string.key'], '/');

        return "/^" . self::CIPHERSWEET_KEY_NAME . "{$escaped}/m";
    }
}
