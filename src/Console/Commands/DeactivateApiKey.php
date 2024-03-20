<?php

namespace Ibra\ApiKey\Console\Commands;

use Ibra\ApiKey\Repositories\ApiKeyRepository;
use Illuminate\Console\Command;

class DeactivateApiKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api_key:deactivate {client_id : The client_id of the API key you want to deactivate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deactivate an API key.';

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
        $client_id = $this->argument('client_id');

        $apiKey = $this->apiKeyRepository->getByClientId($client_id);

        if (!$apiKey) {
            $this->error("API key for client_id: $client_id does not exist.");
            return;
        }

        if ($this->confirm("Are you sure you want to deactivate the API key for client_id: $client_id?")) {
            $this->apiKeyRepository->deactivate($apiKey);
            $this->info("API key for client_id: $client_id has been deactivated.");
        }
    }
}
