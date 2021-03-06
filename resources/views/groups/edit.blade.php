@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/characterCount.js') }}" defer></script>
@endsection

@section('content')
    <h1>Edit Group</h1>
    
    {!! Form::open(['action' => ['App\Http\Controllers\GroupsController@update', $group->name], 'method' => 'POST']) !!}
    <div class="form-group">
        {{Form::label('name', 'Group Name')}}
        {{Form::text('name', $group->name, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('link', 'Link')}}
        <div class="form-control">{{$group->link}}</div>
    </div>

    <div class="form-group">
        {{Form::label('platform', 'Platform')}}
        <div class="form-control">{{$group->platform}}</div>
    </div>

    <div class="form-group">
        {{Form::label('type', 'Type')}}
        {{Form::select('type', [$group->type => $group->type] + $options['type'], null, ['defult' => $group->type, 'class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('privacy', 'Privacy')}}
        {{Form::select('privacy', [$group->privacy => $group->privacy] + $options['privacy'], null, ['defult' => $group->public, 'class' => 'form-control'])}}
    </div>

    <div class="form-group">
        <div class="d-flex">
            {{Form::label('description', 'Description')}}
            <div id='current-count' class='ml-auto text-secondary small'></div>
        </div>
        {{Form::textArea('description', $group->description, ['class' => 'form-control input', 'placeholder' => 'Description'])}}
    </div>

    <div class="d-flex">
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Update Group', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
    
        {!!Form::open(['action'=>['App\Http\Controllers\GroupsController@destroy', $group->name], 'method' => 'POST', 'class' => 'ml-auto'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    </div>

    <a href="/group/{{str_replace(" ", "_", $group->name)}}/view" class="btn btn-default border-dark mt-2">Go Back</a>
@endsection