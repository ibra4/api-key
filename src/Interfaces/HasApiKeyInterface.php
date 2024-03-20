<?php

namespace Ibra\ApiKey\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @interface HasApiKeyInterface
 */
interface HasApiKeyInterface
{
    /**
     * Get all of the model's api keys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function apiKeys(): MorphMany;
}
