@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Google Accounts</span>
                    <a class="btn btn-primary btn-sm" href="{{ route('google.store') }}">
                        Add account
                    </a>
                </div>

                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse ($accounts as $account)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $account->name }}</span>
                                <form action="{{ route('google.destroy', $account) }}" method="post">
                                    {{ csrf_field() }}
                                    {{ method_field('delete') }}

                                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                                        delete
                                    </button>
                                </form>
                            </li>
                        @empty
                            <li class="list-group-item">
                                No google accounts.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
