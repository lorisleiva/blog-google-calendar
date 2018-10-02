<?php

namespace App\Http\Controllers;

use App\Synchronization;
use Illuminate\Http\Request;

class GoogleWebhookController extends Controller
{
    public function __invoke(Request $request)
    {
        if ($request->header('x-goog-resource-state') !== 'exists') {
            return;
        }

        Synchronization::query()
            ->where('id', $request->header('x-goog-channel-id'))
            ->where('resource_id', $request->header('x-goog-resource-id'))
            ->firstOrFail()
            ->ping();
    }
}