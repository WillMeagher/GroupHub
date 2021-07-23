@extends('layouts.app')

@section('content')
    <h1>Request to join {{$group->name}}</h1>
    {!! Form::open(['action' => 'App\Http\Controllers\GroupPermissionsController@store', 'method' => 'POST']) !!}
    
    <div class="form-group">
        {{Form::label('message', 'Message')}}
        {{Form::textArea('message', '', ['class' => 'form-control', 'placeholder' => 'Message'])}}
    </div>

    {{Form::hidden('group_id', $group->id)}}
    {{Form::submit('Send Request', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection