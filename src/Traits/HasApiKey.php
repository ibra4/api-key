<?php

namespace Ibra\ApiKey\Traits;

use Ibra\ApiKey\Models\ApiKey;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasApiKey
{
    /**
     * Get all of the model's api keys.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function apiKeys(): MorphMany
    {
        return $this->morphMany(ApiKey::class, '', 'model', 'model_id');
    }
}
