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
    protected $signature = 'dashboard:rotate-key {--show : shows encryption key}';
    protected $description = 'Create new Cipher Key and Re-Encrypt related Models';

    protected string $keyName = 'CIPHERSWEET_KEY';

    protected array $encryptedModels = [
        AppleSetting::class,
        AppStoreConnectSetting::class,
        GithubSetting::class,
        WorkspaceInviteCode::class,
    ];

    public function handle() : int
    {
        $key = $this->GenerateRandomKey();

        $this->info("Key rotation is working...");
        if ($this->option('show'))
        {
            $this->info("New Key: {$key}");
        }

        // re-encrypt current models
        // after re-encryption, update .env key
        foreach ($this->encryptedModels as $model)
        {
            $this->info("Model: {$model}");
            $this->call('ciphersweet:encrypt', [
                'model' => $model,
                'newKey' => $key
            ]);
        }

        if (!$this->SetKeyInEnvironmentFile($key))
        {
            return Command::FAILURE;
        }

        $this->laravel['config']['ciphersweet.providers.string.key'] = $key;

        $this->info('CipherSweet Key rotation completed!');

        return Command::SUCCESS;
    }

    protected function GenerateRandomKey() : string
    {
        return Hex::encode(random_bytes(32));
    }

    protected function SetKeyInEnvironmentFile($key) : bool
    {
        if (!$this->WriteNewEnvironmentFileWith($key)) { return false; }

        return true;
    }

    protected function WriteNewEnvironmentFileWith($key) : bool
    {
        $replaced = preg_replace(
            pattern: $this->KeyReplacementPattern(),
            replacement: "{$this->keyName}=" . $key,
            subject: $input = file_get_contents($this->laravel->environmentFilePath())
        );

        if ($replaced === $input || $replaced === null)
        {
            $this->error("Unable to set application key. No {$this->keyName} variable was found in the .env file.");
            return false;
        }

        file_put_contents($this->laravel->environmentFilePath(), $replaced);

        return true;
    }

    protected function KeyReplacementPattern() : string
    {
        $escaped = preg_quote('=' . $this->laravel['config']['ciphersweet.providers.string.key'], '/');

        return "/^{$this->keyName}{$escaped}/m";
    }
}
