@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    Events
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($events as $event)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $event->name }}</strong><br>
                                    <span class="text-muted">
                                        @if ($event->allday)
                                            {{ $event->started_at->format('l jS F Y') }}
                                            (all day)
                                        @else
                                            {{ $event->started_at->format('l jS F Y \a\t H:i') }}
                                            ({{ $event->duration }})
                                        @endif
                                    </span>
                                </div>
                                <span
                                    class="badge badge-pill" 
                                    style="background-color: {{ $event->calendar->color }};"
                                >
                                    {{ $event->calendar->name }}
                                </span>
                            </li>
                        @empty
                            <li class="list-group-item">
                                No events.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
