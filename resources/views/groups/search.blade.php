@extends('layouts.app')

@section('content')
    <h1>Search</h1>
    
    {!! Form::open(['action' => 'App\Http\Controllers\GroupsController@results', 'method' => 'POST']) !!}

    <div class="form-group">
        {{Form::label('search', 'Search')}}
        {{Form::text('search', '', ['class' => 'form-control', 'placeholder' => 'Search'])}}
    </div>

    <div class="form-group">
        {{Form::label('platform', 'Platform')}}
        {{Form::select('platform', ['Any' => 'Any'] + $options['platform'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('type', 'Type')}}
        {{Form::select('type', ['Any' => 'Any'] + $options['type'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('privacy', 'Privacy')}}
        {{Form::select('privacy', ['Any' => 'Any'] + $options['privacy'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('sortby', 'Sort By')}}
        {{Form::select('sortby', $options['sortby'], null, ['class' => 'form-control'])}}
    </div>

    {{Form::submit('Search', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection