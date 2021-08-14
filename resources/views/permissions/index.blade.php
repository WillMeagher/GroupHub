@extends('layouts.app')

@section('content')
    <h1>Requests</h1>
    <div class="card mt-3">
        <ul class="list-group list-group-flush">
            @if(count($requests) > 0)
                @foreach($requests as $request)
                    <li class="list-group-item">
                        <h3><a href="/permissions/{{str_replace(" ", "_", $request->id)}}"> <b>{{$request->user_name}}</b> wants to join <b>{{$request->group_name}}</b></a></h3>
                        <small><b>Message:</b> {{$request->message}}</small>
                        <small><b>Created on:</b> {{$request->created_at}}</small>
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