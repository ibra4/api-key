<?php

namespace Ibra\ApiKey\Console\Commands;

use Ibra\ApiKey\Repositories\ApiKeyRepository;
use Illuminate\Console\Command;

class ListApiKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'api_key:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all API keys.';

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
        $this->table(
            ['client_id', 'description', 'model', 'model_id', 'key', 'is_active', 'expires_at', 'created_at'],
            $this->apiKeyRepository->listAllAsTableArray()
        );
    }
}
