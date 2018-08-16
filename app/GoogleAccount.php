<?php

namespace App;

use App\Calendar;
use App\Concerns\Synchronizable;
use App\Jobs\SynchronizeGoogleCalendars;
use App\Jobs\WatchGoogleCalendars;
use App\Services\Google;
use App\User;
use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    use Synchronizable;

    protected $fillable = [
        'google_id', 'name', 'token',
    ];

    protected $casts = [
        'token' => 'json',
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
        SynchronizeGoogleCalendars::dispatch($this);
    }

    public function watch()
    {
        WatchGoogleCalendars::dispatch($this);
    }

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($googleAccount) {
            $googleAccount->calendars->each->delete();
        });

        static::deleted(function ($googleAccount) {
            app(Google::class)->connectUsing($googleAccount->token)->revokeToken();
        });
    }
}
