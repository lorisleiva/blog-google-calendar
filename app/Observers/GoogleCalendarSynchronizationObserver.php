<?php

namespace App\Observers;

use App\Calendar;

class GoogleCalendarSynchronizationObserver
{
    public function created(Calendar $calendar)
    {
        $calendar->synchronization()->create()->ping();
    }

    public function deleting(Calendar $calendar)
    {
        optional($calendar->synchronization)->delete();
    }
}
