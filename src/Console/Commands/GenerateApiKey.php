<?php

namespace Ibra\ApiKey\Console\Commands;

use Exception;
use Ibra\ApiKey\Interfaces\HasApiKeyInterface;
use Ibra\ApiKey\Repositories\ApiKeyRepository;
use Illuminate\Console\Command;
use Illuminate\Database\UniqueConstraintViolationException;
use Throwable;

/**
 * @class GenerateApiKey
 *
 * @property string $signature
 * @property string $description
 */
class GenerateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api_key:generate {client_id : Cleint\'s name} {id : Model id} {model? : Model type, (default: App\Models\User)} {description? : Meta data for the client.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates a new API key for a given client name.';

    private ApiKeyRepository $apiKeyRepository;

    /**
     * Create a new ApiKey Repository instance.
     *
     * @param ApiKeyRepository $apiKeyRepository
     */
    public function __construct(ApiKeyRepository $apiKeyRepository)
    {
        parent::__construct();
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $clientId = $this->argument('client_id');
        $id = $this->argument('id');
        $model = $this->argument('model');
        if (!$model) {
            $model = 'App\Models\User';
            $this->info('Model not provided, defaulting to [App\Models\User].');
        }

        try {
            $this->validateArguments($model, $clientId);
            $authenticatableModel = $this->getAuthenticatableModel($model, $id);
            $this->generateApiKeyForModel($authenticatableModel, $clientId);
        } catch (Throwable $th) {
            $this->error($th->getMessage());
            exit(1);
        }
    }

    /**
     * Generates a new API key for a given client name.
     *
     * @param HasApiKeyInterface $model
     * @param mixed $id
     * @param string $clientId
     *
     * @throws Exception
     * @return void
     */
    private function generateApiKeyForModel(HasApiKeyInterface $model, string $clientId)
    {
        $modelClassName = get_class($model);
        $id = $model->id;

        $key = bin2hex(random_bytes(32));

        $apiKey = $this->apiKeyRepository->getByModelAndModelId($modelClassName, $id);

        if ($apiKey) {
            if (!$this->confirm(sprintf('API key already exists for client name [%s], do you want to replace it?', $clientId))) {
                $this->info('API key generation aborted.');
                return;
            }

            $apiKey->delete();
        }

        try {
            $apiKey = $this->apiKeyRepository->create(
                $model,
                $key,
                $clientId,
                now()->addSeconds(config('api_key.expires')),
                $this->argument('description')
            );
        } catch (UniqueConstraintViolationException $th) {
            throw new Exception(sprintf('API key already exists for client name [%s], Original Exeption: [%s].', $clientId, $th->getMessage()));
        } catch (Throwable $th) {
            throw new Exception(sprintf('Error while trying to generate the API key, Original Exeption: [%s].', $th->getMessage()));
        }

        $this->info(sprintf('API key generated successfully: [%s].', $apiKey->key));
    }

    /**
     * Validates the given arguments.
     *
     * @param string $model
     * @param mixed $id
     * @param string $clientId
     *
     * @throws Exception
     * @return void
     */
    private function validateArguments(string $model, string $clientId)
    {
        if (empty($clientId)) {
            throw new Exception('Client name is required.');
        }

        if ($model && !class_exists($model)) {
            throw new Exception(sprintf('Model class [%s] does not exist.', $model));
        }

        $modelClass = new $model;
        if (!$modelClass instanceof HasApiKeyInterface) {
            throw new Exception(sprintf('Model [%s] must implement the \Ibra\ApiKey\Interfaces\HasApiKeyInterface interface.', $model));
        }
    }

    /**
     * Get the authenticatable model.
     *
     * @param string $model
     * @param mixed $id
     *
     * @throws Exception
     * @return HasApiKeyInterface
     */
    private function getAuthenticatableModel(string $model, mixed $id)
    {
        try {
            return $model::findOrFail($id);
        } catch (Throwable $th) {
            throw new Exception(sprintf('Error while trying to find the model, Original Exeption: [%s].', $th->getMessage()));
        }
    }
}
