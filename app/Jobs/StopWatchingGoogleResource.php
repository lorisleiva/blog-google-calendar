<?php

namespace App\Jobs;

use Illuminate\Foundation\Bus\Dispatchable;

class StopWatchingGoogleResource
{
    use Dispatchable;

    protected $synchronization;

    public function __construct($synchronization)
    {
        $this->synchronization = $synchronization;
    }

    public function handle()
    {
        if (! $this->synchronization->resource_id) {
            return;
        }

        $this->synchronization->synchronizable
            ->getGoogleService('Calendar')
            ->channels->stop($this->synchronization->asGoogleChannel());
    }
}
