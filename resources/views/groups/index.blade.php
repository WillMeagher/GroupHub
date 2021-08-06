@extends('layouts.app')

@section('content')
    <h1>{{isset($title) ? $title : 'Groups'}}</h1>
        @if(count($groups) > 0)
            <div class="card">
                <ul class="list-group list-group-flush">
                @foreach($groups as $group)
                    <li class="list-group-item">
                        <h3><a href="/group/{{$group->id}}"> Name: {{$group->name}} Creator: {{$group->creator_name}}</a></h3>
                        <small>Created on {{$group->created_at}}</small>
                        <small>Score {{$group->score}}</small>
                    </li>
                @endforeach
                </ul>
            </div>
        @endif
@endsection