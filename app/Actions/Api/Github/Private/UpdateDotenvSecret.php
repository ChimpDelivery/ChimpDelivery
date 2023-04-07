<?php

namespace App\Actions\Api\Github\Private;

use Lorisleiva\Actions\Concerns\AsAction;

use GrahamCampbell\GitHub\Facades\GitHub;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Config;

use App\Models\Workspace;
use App\Services\GitHubService;

/// Updates DOTENV secret variable in GitHub Repository environment.
/// This class exist cause of daily key-rotating-system.
/// After key rotation completed, DOTENV secret must be updated.
class UpdateDotenvSecret
{
    use AsAction;

    public const DOTENV_SECRET_NAME = 'TESTENV';

    public function __construct(
        private readonly GitHubService $githubService
    ) {
    }

    public function handle() : JsonResponse
    {
        try
        {
            $secrets = GitHub::api('deployment')->environments()->secrets();

            // get current public key for specified environment
            $repoPublicKey = $secrets->publicKey(config('github.app_repository_id'), 'staging');

            // seal new dotenv
            $message = File::get(App::environmentFilePath());
            $publicKey = base64_decode($repoPublicKey['key']);
            $sealed = sodium_crypto_box_seal($message, $publicKey);

            // send request
            $response = $secrets->createOrUpdate(
                config('github.app_repository_id'),
                'staging',
                self::DOTENV_SECRET_NAME,
                [
                    'encrypted_value' => base64_encode($sealed),
                    'key_id' => $repoPublicKey['key_id']
                ]
            );
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'response' => [
                    'error' => [
                        'error_code' => $exception->getCode(),
                        'error_msg' => $exception->getMessage(),
                    ],
                ],
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }

    public function asController() : JsonResponse
    {
        Config::set(
            'github.connections.main.token',
            Workspace::find(config('workspaces.internal_ws_id'))->githubSetting->personal_access_token
        );

        return $this->handle();
    }
}
