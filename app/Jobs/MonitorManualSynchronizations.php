<?php

namespace App\Jobs;

use App\Synchronization;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MonitorManualSynchronizations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $refreshInterval;

    public function __construct($refreshInterval = 15)
    {
        $this->refreshInterval = $refreshInterval;
    }

    public function handle()
    {
        Synchronization::query()
            ->whereNull('resource_id')
            ->where('last_synchronized_at', '<', now()->subMinutes($this->refreshInterval))
            ->get()
            ->each->ping();
    }
}
