<?php

namespace App\Http\Controllers;

use App\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $events = auth()->user()->events()
            ->orderBy('started_at', 'desc')
            ->get();

        return view('events', compact('events'));
    }
}
