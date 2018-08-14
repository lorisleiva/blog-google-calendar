<?php

namespace App\Observers;

use App\GoogleAccount;
use App\Services\Google;

class GoogleAccountSynchronizationObserver
{
    public function created(GoogleAccount $googleAccount)
    {
        $googleAccount->synchronization()->create()->ping();
    }

    public function deleting(GoogleAccount $googleAccount)
    {
        optional($googleAccount->synchronization)->delete();

        $googleAccount->calendars->each->delete();
        
        app(Google::class)->connectUsing($googleAccount->token)->revokeToken();
    }
}
