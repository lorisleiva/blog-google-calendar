<?php

namespace App\Concerns;

use App\Synchronization;

trait Synchronizable
{
    public static function bootSynchronizable()
    {
        // Start a new synchronization once created.
        static::created(function ($synchronizable) {
            $synchronizable->synchronization()->create();
        });

        // Stop and delete associated synchronization.
        static::deleting(function ($synchronizable) {
            optional($synchronizable->synchronization)->delete();
        });
    }

    public function synchronization()
    {
        return $this->morphOne(Synchronization::class, 'synchronizable');
    }
    
    abstract public function synchronize();
}