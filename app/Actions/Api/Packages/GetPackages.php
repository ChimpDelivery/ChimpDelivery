<?php

namespace App\Actions\Api\Packages;

use Lorisleiva\Actions\Concerns\AsAction;

use Illuminate\Http\JsonResponse;

use App\Models\Package;

class GetPackages
{
    use AsAction;

    public function handle() : JsonResponse
    {
        return response()->json([
            'packages' => Package::all([ 'package_id', 'url', 'hash' ])->values()
        ]);
    }
}
