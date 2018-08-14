<?php

namespace App\Concerns;

use App\Services\Google;
use App\Synchronization;

trait Synchronizable
{
    public function synchronization()
    {
        return $this->morphOne(Synchronization::class, 'synchronizable');
    }

    public function getGoogleService($service)
    {
        return app(Google::class)
            ->connectUsing($this->getGoogleToken())
            ->service($service);
    }

    abstract public function getGoogleToken();
    abstract public function watch(Synchronization $synchronization);
    abstract public function synchronize();
}