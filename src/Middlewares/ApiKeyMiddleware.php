<?php

namespace Ibra\ApiKey\Middlewares;

use Ibra\ApiKey\Models\ApiKey;
use Illuminate\Support\Facades\Auth;

class ApiKeyMiddleware
{
    public function handle($request, $next)
    {
        $apiKeyHeader = $request->header('X-API-KEY');
        $apiKey = ApiKey::where('key', $apiKeyHeader)->first();

        if (!$apiKey) {
            abort(401, 'Unauthenticated');
        }

        if (!$apiKey->is_active) {
            abort(403, 'Api key is not active');
        }

        $model = $apiKey->model;
        $user = $model::find($apiKey->model_id);
        Auth::login($user);

        return $next($request);
    }
}
