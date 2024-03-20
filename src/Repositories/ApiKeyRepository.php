<?php

namespace Ibra\ApiKey\Repositories;

use Ibra\ApiKey\Interfaces\HasApiKeyInterface;
use Ibra\ApiKey\Models\ApiKey;

/**
 * @class ApiKeyRepository
 */
class ApiKeyRepository
{
    /**
     * Get an API key by client_id.
     *
     * @param string $client_id
     *
     * @return ApiKey
     */
    public function getByClientId(string $client_id)
    {
        return ApiKey::where('client_id', $client_id)->first();
    }

    /**
     * Get an API key by model and model_id.
     *
     * @param string $model
     * @param mixed $model_id
     *
     * @return ApiKey
     */
    public function getByModelAndModelId(string $model, mixed $model_id)
    {
        return ApiKey::where('model', $model)->where('model_id', $model_id)->first();
    }

    /**
     * Create a new API key.
     *
     * @param HasApiKeyInterface $model
     * @param string $key
     * @param string $clientId
     * @param \Illuminate\Support\Carbon $expiresAt
     * @param int $expiresAt
     * @param string|null $description
     *
     * @return ApiKey
     */
    public function create(HasApiKeyInterface $model, string $key, string $clientId, \Illuminate\Support\Carbon $expiresAt, string $description = null)
    {
        return $model->apiKeys()->create([
            'key' => $key,
            'client_id' => $clientId,
            'expires_at' => $expiresAt,
            'description' => $description
        ]);
    }

    /**
     * Deactivate an API key.
     *
     * @param ApiKey $apiKey
     *
     * @return bool
     */
    public function deactivate(ApiKey $apiKey)
    {
        return $apiKey->update(['is_active' => false]);
    }

    /**
     * Activate an API key.
     *
     * @param ApiKey $apiKey
     *
     * @return bool
     */
    public function activate(ApiKey $apiKey)
    {
        return $apiKey->update(['is_active' => false]);
    }

    /**
     * Remove an API key.
     *
     * @param ApiKey $apiKey
     *
     * @return bool
     */
    public function remove(ApiKey $apiKey)
    {
        return $apiKey->delete();
    }

    /**
     * List all API keys as an array.
     *
     * @return array
     */
    public function listAllAsTableArray()
    {
        return ApiKey::select('client_id', 'description', 'model', 'model_id', 'key', 'is_active', 'expires_at', 'created_at')->get()->toArray();
    }
}
