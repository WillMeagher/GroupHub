@extends('layouts.app')

@section('content')
    <h1>{{isset($title) ? $title : 'Users'}}</h1>
    <div class="card mt-3">
        <ul class="list-group list-group-flush">
            @if(count($users) > 0)
                @foreach($users as $user)
                    <li class="list-group-item">
                        <h3><a href="/account/{{str_replace(" ", "_", $user->name)}}"> Name: {{$user->name}}</a></h3>
                        <small>Account created on {{$user->created_at->format('M d, Y')}}</small>
                    </li>
                @endforeach
            @else
                <li class="list-group-item">
                    <h3 class="m-2">No Results</h3>
                </li>
            @endif
        </ul>
    </div>
@endsection