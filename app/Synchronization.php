<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class Synchronization extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'token', 'last_synchronized_at', 'resource_id', 'expired_at'
    ];

    protected $casts = [
        'last_synchronized_at' => 'datetime',
        'expired_at' => 'datetime',
    ];
    
    public function ping()
    {
        return $this->synchronizable->synchronize();
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

    public function synchronizable()
    {
        return $this->morphTo();
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($synchronization) {
            $synchronization->id = Uuid::uuid4();
            $synchronization->last_synchronized_at = now();
        });

        static::created(function ($synchronization) {
            $synchronization->ping();
        });
    }
}
