@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/characterCount.js') }}" defer></script>
@endsection

@section('content')
    <h1>Create Group</h1>
    
    {!! Form::open(['action' => 'App\Http\Controllers\GroupsController@store', 'method' => 'POST']) !!}
    <div class="form-group">
        {{Form::label('name', 'Group Name')}}
        {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Group Name'])}}
    </div>

    <div class="form-group">
        {{Form::label('link', 'Link')}}
        {{Form::text('link', '', ['class' => 'form-control', 'placeholder' => 'Link'])}}
    </div>

    <div class="form-group">
        {{Form::label('platform', 'Platform')}}
        {{Form::select('platform', $options['platform'], null, ['placeholder' => 'Select the platform', 'class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('type', 'Type')}}
        {{Form::select('type', $options['type'], null, ['placeholder' => 'Select the type of Group', 'class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('privacy', 'Privacy')}}
        {{Form::select('privacy', $options['privacy'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        <div class="d-flex">
            {{Form::label('description', 'Description')}}
            <div id='current-count' class='ml-auto text-secondary small'></div>
        </div>
        {{Form::textArea('description', '', ['class' => 'form-control input', 'placeholder' => 'Description'])}}
    </div>

    {{Form::submit('Create Group', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection