<?php

namespace App;

use App\Jobs\StopWatchingGoogleResource;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Synchronization extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'resource_id', 'token', 'expired_at', 'last_synchronized_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'last_synchronized_at' => 'datetime',
    ];

    public function synchronizable()
    {
        return $this->morphTo();
    }

    public function ping()
    {
        return $this->synchronizable->synchronize();
    }

    public function startPushNotifications()
    {
        $this->synchronizable->watch();
    }

    public function stopPushNotifications()
    {
        StopWatchingGoogleResource::dispatchNow($this);
    }

    public function refreshPushNotifications()
    {
        $synchronizable = $this->synchronizable;
        $this->delete();
        $synchronizable->synchronization()->create();
    }

    public function asGoogleChannel()
    {
        return tap(new \Google_Service_Calendar_Channel(), function ($channel) {
            $channel->setId($this->id);
            $channel->setResourceId($this->resource_id);
            $channel->setType('web_hook');
            $channel->setAddress(config('services.google.webhook_uri'));
        });
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($synchronization) {
            $synchronization->id = Uuid::uuid4();
            $synchronization->last_synchronized_at = now();
        });

        static::created(function ($synchronization) {
            $synchronization->startPushNotifications();
            $synchronization->ping();
        });

        static::deleting(function ($synchronization) {
            $synchronization->stopPushNotifications();
        });
    }
}
