<?php

namespace App\Jobs;

use App\Jobs\WatchGoogleResource;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WatchGoogleEvents extends WatchGoogleResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function getGoogleRequest($service, $channel)
    {
        return $service->events->watch(
            $this->synchronizable->google_id, $channel
        );
    }
}