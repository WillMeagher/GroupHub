@extends('layouts.app')

@section('content')
    <h1>THIS IS THE JOIN PAGE</h1>
    <h1>name: {{$group->name}}</h1>
    <h4>link: {{$group->link}}</h4>

    <hr>
    <small>Created on {{$group->created_at->format('M d, Y')}}</small>
    <hr>
    <a href="/group/{{str_replace(" ", "_", $group->name)}}/view" class="btn btn-default border-dark">Go Back</a>
@endsection