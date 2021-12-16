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
    <h2>Link: {{$group->link}}</h2>
    <hr>
    <h4>Created on {{$group->created_at->format('M d, Y')}}</h4>
    <hr>
    <a href="/group/{{str_replace(" ", "_", $group->name)}}/view" class="btn btn-default border-dark">Go Back</a>
@endsection