@extends('layouts.app')

@section('content')

    <h4>User: {{$request->user_name}}</h4>
    <h4>Group: {{$request->group_name}}</h4>
    <h4>Message: {{$request->message}}</h4>

    <hr>
    <small>Created on {{$request->created_at}}</small>
    <hr>

    {!! Form::open(['action' => ['App\Http\Controllers\GroupPermissionsController@update', $request->id], 'method' => 'POST']) !!}
    {{Form::hidden('_method', 'PUT')}}
    {{Form::hidden('group_id', $request->group_id)}}
    {{Form::button('Accept', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'status', 'value' => 'Accepted'])}}
    {{Form::button('Deny', ['type' => 'submit', 'class' => 'btn btn-primary', 'name' => 'status', 'value' => 'Denied'])}}
    {!! Form::close() !!}

    <a href="/permissions" class="btn btn-default">Go Back</a>

@endsection