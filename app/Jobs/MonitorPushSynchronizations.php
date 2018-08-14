<?php

namespace App\Jobs;

use App\Synchronization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonitorPushSynchronizations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $refreshInterval;

    public function __construct($refreshInterval = 5)
    {
        $this->refreshInterval = $refreshInterval;
    }

    public function handle()
    {
        Synchronization::query()
            ->whereNotNull('resource_id')
            ->whereNull('expired_at')
            ->orWhere('expired_at', '<', now()->subDays($this->refreshInterval))
            ->get()
            ->each->refresh();
    }
}
