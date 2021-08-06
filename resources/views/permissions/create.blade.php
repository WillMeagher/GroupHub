@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/characterCount.js') }}" defer></script>
@endsection

@section('content')
    <h1>Request to join {{$group->name}}</h1>
    
    {!! Form::open(['action' => 'App\Http\Controllers\PermissionsController@store', 'method' => 'POST']) !!}
    
    <div class="form-group">
        <div class="d-flex">
            {{Form::label('message', 'Message')}}
            <div id='current-count' class='ml-auto text-secondary small'></div>
        </div>
        {{Form::textArea('message', '', ['class' => 'form-control input', 'placeholder' => 'Message'])}}
    </div>

    {{Form::hidden('group_id', $group->id)}}
    {{Form::submit('Send Request', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection