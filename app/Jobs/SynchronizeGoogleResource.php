<?php

namespace App\Jobs;

abstract class SynchronizeGoogleResource
{
    protected $synchronizable;

    public function __construct($synchronizable)
    {
        $this->synchronizable = $synchronizable;
    }

    public function handle()
    {
        $pageToken = null;
        $syncToken = $this->getSyncToken();
        $service = $this->synchronizable->getGoogleService('Calendar');

        do {
            $tokens = compact('pageToken', 'syncToken');

            try {
                $list = $this->getGoogleRequest($service, $tokens);
            } catch (\Google_Service_Exception $e) {
                if ($e->getCode() === 410) {
                    $this->setSyncToken(null);
                    $this->dropAllSyncedItems();
                    return $this->handle();
                }
                throw $e;
            }

            foreach ($list->getItems() as $item) {
                $this->syncItem($item);
            }

            $pageToken = $list->getNextPageToken();
        } while ($pageToken);

        $this->setSyncToken($list->getNextSyncToken());
    }

    public function getSyncToken()
    {
        return $this->synchronizable->synchronization->token;
    }

    public function setSyncToken($token)
    {
        $data = compact('token');

        if ($token) {
            $data['last_synchronized_at'] = now();
        }

        $this->synchronizable->synchronization->update($data);
        
        return $this;
    }

    abstract public function getGoogleRequest($service, $tokens);
    abstract public function syncItem($item);
    abstract public function dropAllSyncedItems();
}
