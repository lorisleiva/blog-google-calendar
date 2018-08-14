<?php

namespace App;

use App\Calendar;
use App\Concerns\Synchronizable;
use App\Jobs\SynchronizeGoogleCalendars;
use App\Jobs\WatchGoogleCalendars;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    use Synchronizable;

    protected $fillable = [
        'google_id', 'name', 'token'
    ];

    protected $casts = [
        'token' => 'json'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function calendars()
    {
        return $this->hasMany(Calendar::class);
    }

    public function getGoogleToken()
    {
        return $this->token;
    }

    public function synchronize()
    {
        return SynchronizeGoogleCalendars::dispatch($this);
    }

    public function watch(Synchronization $synchronization)
    {
        return WatchGoogleCalendars::dispatchNow($synchronization);
    }
}
