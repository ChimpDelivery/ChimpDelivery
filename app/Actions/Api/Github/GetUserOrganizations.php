<?php

namespace App\Actions\Api\Github;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

use App\Services\GitHubService;

class GetUserOrganizations
{
    use AsAction;

    public function handle() : JsonResponse
    {
        $response = [];

        try
        {
            $response = app(GitHubService::class)->GetUserOrganizations();
        }
        catch (\Exception $exception)
        {
            return response()->json([
                'response' => $exception->getMessage(),
                'error_code' => $exception->getCode(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json([ 'response' => $response ], Response::HTTP_OK);
    }
}
