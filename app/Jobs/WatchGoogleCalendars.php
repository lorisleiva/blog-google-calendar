<?php

namespace App\Jobs;

use App\Jobs\WatchGoogleResource;
use Illuminate\Foundation\Bus\Dispatchable;

class WatchGoogleCalendars extends WatchGoogleResource
{
    use Dispatchable;
    
    public function getGoogleRequest($service, $channel)
    {
        return $service->calendarList->watch($channel);
    }
}
