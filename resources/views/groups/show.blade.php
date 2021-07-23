@extends('layouts.app')

@section('content')
    <h1>name: {{$group->name}}</h1>
    <hr>
    <h4>platform: {{$group->platform}}</h4>
    <h4>size: {{$size}}</h4>
    <h4>type: {{$group->type}}</h4>
    <h4>privacy: {{$group->privacy}}</h4>
    <h4>creator: {{$group->creator_name}}</h4>
    <h4 class='text-wrap'>description: {{$group->description}}</h4>

    <hr>
    <small>Created on {{$group->created_at}}</small>
    <hr>
    <a href="/group" class="btn btn-default">Go Back</a>
    @if(!Auth::guest() && Auth::user()->id == $group->creator_id)
        <a href="/group/{{$group->id}}/edit" class="btn btn-default">Edit</a>
    @else
        <a href="/permissions/{{$group->id}}/create" class="btn btn-default">Join</a>
    @endif
@endsection