@extends('layouts.app')

@section('content')
    <h1>Requests</h1>
        @if(count($requests) > 0)
            <div class="card">
                <ul class="list-group list-group-flush">
                @foreach($requests as $request)
                    <li class="list-group-item">
                        <h3><a href="/permissions/{{$request->id}}"> <b>{{$request->user_name}}</b> wants to join <b>{{$request->group_name}}</b></a></h3>
                        <small><b>Message:</b> {{$request->message}}</small>
                        <small><b>Created on:</b> {{$request->created_at}}</small>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif
@endsection