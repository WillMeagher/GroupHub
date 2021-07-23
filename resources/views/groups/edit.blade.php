@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/groupCreate.js') }}" defer></script>
@endsection

@section('content')
    <h1>Edit Group</h1>
    {!! Form::open(['action' => ['App\Http\Controllers\GroupsController@update', $group->id], 'method' => 'POST']) !!}
    <div class="form-group">
        {{Form::label('name', 'Group Name')}}
        {{Form::text('name', $group->name, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('link', 'Link')}}
        {{Form::text('link', $group->link, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('platform', 'Platform')}}
        {{Form::select('platform', [$group->platform => $group->platform] + $options['platform'], null, ['defult' => $group->platform, 'class' => 'form-control'])}}
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
        {{Form::textArea('description', $group->description, ['class' => 'form-control', 'placeholder' => 'Description'])}}
    </div>

    <div class="d-flex">
        {{Form::hidden('_method', 'PUT')}}
        {{Form::submit('Update Group', ['class' => 'btn btn-primary'])}}
        {!! Form::close() !!}
    
        {!!Form::open(['action'=>['App\Http\Controllers\GroupsController@destroy', $group->id], 'method' => 'POST', 'class' => 'pull-right'])!!}
        {{Form::hidden('_method', 'DELETE')}}
        {{Form::submit('Delete', ['class' => 'btn btn-danger'])}}
        {!!Form::close()!!}
    </div>

    <a href="/group/{{$group->id}}" class="btn btn-default">Go Back</a>

@endsection