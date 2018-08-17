<?php

namespace App\Jobs;

use App\Jobs\SynchronizeGoogleResource;
use App\Services\Google;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SynchronizeGoogleCalendars extends SynchronizeGoogleResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $googleAccount;

    public function __construct($googleAccount)
    {
        $this->googleAccount = $googleAccount;
    }

    public function getGoogleService()
    {
        return app(Google::class)
            ->connectUsing($this->googleAccount->token)
            ->service('Calendar');
    }

    public function getGoogleRequest($service, $options)
    {
        return $service->calendarList->listCalendarList($options);
    }

    public function syncItem($googleCalendar)
    {
        if ($googleCalendar->deleted) {
            return $this->googleAccount->calendars()
                ->where('google_id', $googleCalendar->id)
                ->get()->each->delete();
        }

        $this->googleAccount->calendars()->updateOrCreate(
            [
                'google_id' => $googleCalendar->id,
            ],
            [
                'name' => $googleCalendar->summary,
                'color' => $googleCalendar->backgroundColor,
                'timezone' => $googleCalendar->timeZone,
            ]
        );
    }
}
