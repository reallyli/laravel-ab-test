<?php

namespace Reallyli\AB\Models;

use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Goal extends Eloquent
{
    protected $fillable = ['name', 'experiment', 'count'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Set the connection based on the config.
        $this->connection = Config::get('ab::connection');
    }

    public function scopeActive($query)
    {
        if ($experiments = Config::get('ab::experiments')) {
            return $query->whereIn('experiment', Config::get('ab::experiments'));
        }

        return $query;
    }
}
