<?php

namespace App\Jobs;

abstract class SynchronizeGoogleResource
{
    public function handle()
    {
        $pageToken = null;
        $service = $this->getGoogleService();

        do {
            $list = $this->getGoogleRequest($service, compact('pageToken'));

            foreach ($list->getItems() as $item) {
                $this->syncItem($item);
            }

            $pageToken = $list->getNextPageToken();
        } while ($pageToken);
    }

    abstract public function getGoogleService();
    abstract public function getGoogleRequest($service, $options);
    abstract public function syncItem($item);
}
