<?php

namespace App;

use App\Jobs\GoogleSynchronization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
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

    public function initialize()
    {
        $this->id = Uuid::uuid4();
        $this->last_synchronized_at = now();
        $this->synchronizable->watch($this);

        return $this;
    }

    public function refresh()
    {
        \DB::transaction(function () {
            $this->delete();
            $this->synchronizable->synchronization()->create()->ping();
        });
    }

    public function delete()
    {
        try {
            $this->synchronizable
                ->getGoogleService('Calendar')
                ->channels->stop($this->asGoogleChannel());
        } catch (\Google_Service_Exception $e) {
            //
        }

        return parent::delete();
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

        // Initialize before persisting to the database.
        static::creating(function ($synchronization) {
            $synchronization->initialize();
        });
    }
}
