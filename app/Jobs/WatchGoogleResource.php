<?php

namespace App\Jobs;

use Illuminate\Support\Carbon;

abstract class WatchGoogleResource
{
    protected $synchronization;

    public function __construct($synchronization)
    {
        $this->synchronization = $synchronization;
    }

    public function handle()
    {
        try {
            $channel = $this->getGoogleRequest(
                $this->synchronization->synchronizable->getGoogleService('Calendar'),
                $this->synchronization->asGoogleChannel()
            );

            $this->synchronization->resource_id = $channel->getResourceId();
            $this->synchronization->expired_at = Carbon::createFromTimestampMs(
                $channel->getExpiration()
            );
        } catch (\Google_Service_Exception $e) {
            // If we reach an error at this point, it is likely that
            // push notifications are not allowed for this resource.
            // Instead we will sync it manually at regular interval.
        }
    }

    abstract public function getGoogleRequest($service, $channel);
}
