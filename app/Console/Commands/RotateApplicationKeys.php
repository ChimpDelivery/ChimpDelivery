<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use ParagonIE\ConstantTime\Hex;

use Illuminate\Support\Carbon;

use App\Models\AppleSetting;
use App\Models\AppStoreConnectSetting;
use App\Models\GithubSetting;
use App\Models\WorkspaceInviteCode;

class RotateApplicationKeys extends Command
{
    protected $signature = 'dashboard:rotate-key {--show : shows encryption keys}';
    protected $description = 'Rotates encryption keys in application.';

    protected string $cipherSweetKeyName = 'CIPHERSWEET_KEY';

    protected array $encryptedModels = [
        AppleSetting::class,
        AppStoreConnectSetting::class,
        GithubSetting::class,
        WorkspaceInviteCode::class,
    ];

    public function handle() : int
    {
        // maintenance mode
        $this->call('down', [ '--secret' => config('app.down_secret') ]);
        $this->info(Carbon::now() . ' | ' . 'Key Rotation is starting...');

        // rotate laravel key
        $this->call('key:generate', [ '--force' => true ]);

        // rotate ciphersweet key
        $oldKey = config('ciphersweet.providers.string.key');
        $key = $this->GenerateRandomKey();

        $this->info('CipherSweet Key rotation is working...');
        if ($this->option('show'))
        {
            $this->info("New CipherSweet Key: {$key}");
        }

        $this->EncryptModels($key);

        // revert back when failed
        if (!$this->WriteNewEnvironmentFileWith($key))
        {
            $this->EncryptModels($oldKey);
            $this->call('dashboard:up');

            return Command::FAILURE;
        }

        $this->laravel['config']['ciphersweet.providers.string.key'] = $key;

        $this->info('All Encryption Keys rotated successfully!');

        /// restarting horizon required
        $this->call('dashboard:restart-horizon');
        $this->call('dashboard:up');

        return Command::SUCCESS;
    }

    protected function EncryptModels(string $key)
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
            replacement: "{$this->cipherSweetKeyName}=" . $key,
            subject: $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null)
        {
            $this->error("Unable to set application key. No {$this->cipherSweetKeyName} variable was found in the .env file.");
            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    protected function KeyReplacementPattern() : string
    {
        $escaped = preg_quote('=' . $this->laravel['config']['ciphersweet.providers.string.key'], '/');

        return "/^{$this->cipherSweetKeyName}{$escaped}/m";
    }
}
