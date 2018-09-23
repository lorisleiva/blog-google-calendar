<?php

namespace App\Jobs;

use App\Jobs\SynchronizeGoogleResource;
use App\Services\Google;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class SynchronizeGoogleEvents extends SynchronizeGoogleResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    
    protected $calendar;

    public function __construct($calendar)
    {
        $this->calendar = $calendar;
    }

    public function getGoogleService()
    {
        return app(Google::class)
            ->connectUsing($this->calendar->googleAccount->token)
            ->service('Calendar');
    }

    public function getGoogleRequest($service, $options)
    {
        return $service->events->listEvents(
            $this->calendar->google_id, $options
        );
    }

    public function syncItem($googleEvent)
    {
        if ($googleEvent->status === 'cancelled') {
            return $this->calendar->events()
                ->where('google_id', $googleEvent->id)
                ->delete();
        }

        $this->calendar->events()->updateOrCreate(
            [
                'google_id' => $googleEvent->id,
            ],
            [
                'name' => $googleEvent->summary ?? '(No title)',
                'description' => $googleEvent->description,
                'allday' => $this->isAllDayEvent($googleEvent), 
                'started_at' => $this->parseDatetime($googleEvent->start), 
                'ended_at' => $this->parseDatetime($googleEvent->end), 
            ]
        );
    }

    protected function isAllDayEvent($googleEvent)
    {
        return ! $googleEvent->start->dateTime && ! $googleEvent->end->dateTime;
    }

    protected function parseDatetime($googleDatetime)
    {
        $rawDatetime = $googleDatetime->dateTime ?: $googleDatetime->date;

        return Carbon::parse($rawDatetime)->setTimezone('UTC');
    }
}