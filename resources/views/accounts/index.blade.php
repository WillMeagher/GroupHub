@extends('layouts.app')

@section('content')
    <h1>{{isset($title) ? $title : 'Users'}}</h1>
        @if(count($users) > 0)
            <div class="card">
                <ul class="list-group list-group-flush">
                @foreach($users as $user)
                    <li class="list-group-item">
                        <h3><a href="/account/{{$user->name}}"> Name: {{$user->name}}</a></h3>
                        <small>Account created on {{$user->created_at}}</small>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif
@endsection