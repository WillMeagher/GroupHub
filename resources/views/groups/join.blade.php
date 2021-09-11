@extends('layouts.app')

@section('content')
    <h1>{{$group->name}}</h1>
    <hr>
    @if ($group->description != "")
        <h4 class='text-wrap'>Description: {{$group->description}}</h4>
    @endif
    <h4>Creator: {{$group->creator_name}}</h4>
    <h4>Type: {{$group->type}}</h4>
    <h4>Platform: {{$group->platform}}</h4>
    <h4>Size: {{$group->size}}</h4>
    <h4>Privacy: {{$group->privacy}}</h4>
    <h2>Link: <a href="{{$group->link}}">{{$group->link}}</a></h2>
    <hr>
    <small>Created on {{$group->created_at->format('M d, Y')}}</small>
    <hr>
    <a href="/group/{{str_replace(" ", "_", $group->name)}}/view" class="btn btn-default">Go Back</a>
@endsection