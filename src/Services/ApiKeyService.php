<?php

namespace Ibra\ApiKey\Services;

use Ibra\ApiKey\Interfaces\HasApiKeyInterface;
use Ibra\ApiKey\Models\ApiKey;
use Ibra\ApiKey\Repositories\ApiKeyRepository;

/**
 * @class ApiKeyService
 */
class ApiKeyService
{
    /**
     * Instance of ApiKeyRepository.
     *
     * @var ApiKeyRepository
     */
    protected $apiKeyRepository;

    /**
     * ApiKeyService constructor.
     *
     * @param ApiKeyRepository $apiKeyRepository
     */
    public function __construct(ApiKeyRepository $apiKeyRepository)
    {
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * Get an API key by id.
     *
     * @param mixed $id
     * @return ApiKey
     */
    public function getById(mixed $id)
    {
        return $this->apiKeyRepository->getByClientId($id);
    }

    /**
     * Get an API key by client_id.
     *
     * @param string $client_id
     * @return ApiKey
     */
    public function getByClientId(string $client_id)
    {
        return $this->apiKeyRepository->getByClientId($client_id);
    }

    /**
     * Get an API key by model and model_id.
     *
     * @param string $model
     * @param mixed $model_id
     * @return ApiKey
     */
    public function getByModelAndModelId(string $model, mixed $model_id)
    {
        return $this->apiKeyRepository->getByModelAndModelId($model, $model_id);
    }

    /**
     * Create a new API key.
     *
     * @param HasApiKeyInterface $model
     * @param string $key
     * @param string $clientId
     * @param \Illuminate\Support\Carbon $expiresAt
     * @param string|null $description
     * @return ApiKey
     */
    public function create(HasApiKeyInterface $model, string $key, string $clientId, string $description = null)
    {
        $expiresAt = now()->addSeconds(config('api_key.expires'));
        return $this->apiKeyRepository->create($model, $key, $clientId, $expiresAt, $description);
    }

    /**
     * Deactivate an API key.
     *
     * @param ApiKey $apiKey
     * @return bool
     */
    public function deactivate(ApiKey $apiKey)
    {
        return $this->apiKeyRepository->deactivate($apiKey);
    }

    /**
     * Activate an API key.
     *
     * @param ApiKey $apiKey
     * @return bool
     */
    public function activate(ApiKey $apiKey)
    {
        return $this->apiKeyRepository->activate($apiKey);
    }

    /**
     * Remove an API key.
     *
     * @param ApiKey $apiKey
     * @return bool
     */
    public function remove(ApiKey $apiKey)
    {
        return $this->apiKeyRepository->activate($apiKey);
    }
}
