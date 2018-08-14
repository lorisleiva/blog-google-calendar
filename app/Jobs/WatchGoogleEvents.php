<?php

namespace App\Jobs;

use App\Jobs\WatchGoogleResource;
use Illuminate\Foundation\Bus\Dispatchable;

class WatchGoogleEvents extends WatchGoogleResource
{
    use Dispatchable;

    public function getGoogleRequest($service, $channel)
    {
        return $service->events->watch(
            $this->synchronization->synchronizable->google_id, $channel
        );
    }
}
