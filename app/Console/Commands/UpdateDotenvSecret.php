<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use GrahamCampbell\GitHub\Facades\GitHub;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

use App\Models\Workspace;

/// Updates DOTENV secret variable in GitHub Repository environment.
/// This class exist cause of daily key-rotating-system.
/// After key rotation completed, DOTENV secret must be updated.
class UpdateDotenvSecret extends Command
{
    protected $signature = 'dashboard:update-dotenv-secret {env : target environment}';
    protected $description = 'Rotates CipherSweet Encryption Key in application.';

    // must be synced with github repository environments
    // todo: api-based implementation
    public const ENVIRONMENTS = [
        'staging',
        'production'
    ];

    public function handle() : int
    {
        $targetEnv = $this->argument('env');

        if (!in_array($targetEnv, self::ENVIRONMENTS))
        {
            $this->error("Error: {$targetEnv} environment could not found!");
            return Command::FAILURE;
        }

        try
        {
            $this->PrepareConnectionToken();

            $secrets = GitHub::api('deployment')->environments()->secrets();

            // get current public key for specified environment
            $repoPublicKey = $secrets->publicKey(config('deploy.repository_id'), $targetEnv);

            // seal new dotenv
            $message = File::get(App::environmentFilePath());
            $publicKey = base64_decode($repoPublicKey['key']);
            $sealed = sodium_crypto_box_seal($message, $publicKey);

            // send request
            $secrets->createOrUpdate(
                config('deploy.repository_id'),
                'staging',
                config('deploy.dotenv_secret_name'),
                [
                    'encrypted_value' => base64_encode($sealed),
                    'key_id' => $repoPublicKey['key_id']
                ]
            );
        }
        catch (\Exception $exception)
        {
            $this->error("Exception Message: {$exception->getMessage()}, Code: {$exception->getCode()}");
            return Command::FAILURE;
        }

        $this->info(config('deploy.dotenv_secret_name') .  " secret in {$targetEnv} environment updated successfully!");
        return Command::SUCCESS;
    }

    private function PrepareConnectionToken() : void
    {
        Config::set(
            'github.connections.main.token',
            Workspace::find(config('workspaces.internal_ws_id'))
                ->githubSetting
                ->personal_access_token
        );
    }
}
