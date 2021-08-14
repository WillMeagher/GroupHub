@extends('layouts.app')

@section('content')
    <h1>name: {{$group->name}}</h1>
    <hr>
    <h4>creator: {{$group->creator_name}}</h4>
    <h4 class='text-wrap'>description: {{$group->description}}</h4>
    <h4>platform: {{$group->platform}}</h4>
    <h4>size: {{$group->size}}</h4>
    <h4>type: {{$group->type}}</h4>
    <h4>privacy: {{$group->privacy}}</h4>
    <h2><b>Link: {{$group->link}}</b></h2>
    <hr>
    <small>Created on {{$group->created_at}}</small>
    <hr>
    <a href="/group/{{str_replace(" ", "_", $group->name)}}" class="btn btn-default">Go Back</a>
@endsection