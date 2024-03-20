<?php

namespace Ibra\ApiKey\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property string $model
 * @property int $model_id
 * @property string $key
 * @property string $client_id
 * @property string $description
 * @property bool $is_active
 * @property \Carbon\Carbon $expires_at
 */
class ApiKey extends Model
{
    protected $fillable = [
        'model',
        'model_id',
        'key',
        'client_id',
        'description',
        'is_active',
        'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'expires_at' => 'datetime'
    ];
}
