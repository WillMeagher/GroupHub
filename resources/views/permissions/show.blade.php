@extends('layouts.app')

@section('content')
    <h4>User: <a href="/account/{{str_replace(" ", "_", $request->user_name)}}">{{$request->user_name}}</a></h4>
    <h4>Group: <a href="/group/{{str_replace(" ", "_", $request->group_name)}}">{{$request->group_name}}</a></h4>
    <h4>Message: {{$request->message}}</h4>

    <hr>
    <small>Created on {{$request->created_at}}</small>
    <hr>

    {!! Form::open(['action' => ['App\Http\Controllers\PermissionsController@update', $request->id], 'method' => 'POST']) !!}
    {{Form::hidden('_method', 'PUT')}}
    {{Form::hidden('name', $request->group_name)}}
    {{Form::button('Accept', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'status', 'value' => 'Accepted'])}}
    {{Form::button('Deny', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'status', 'value' => 'Denied'])}}
    {!! Form::close() !!}

    <a href="/permissions" class="btn btn-default">Go Back</a>
@endsection