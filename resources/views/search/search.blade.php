@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/hideQuestions.js') }}" defer></script>
@endsection

@section('content')
    <h1>Search</h1>
    
    {!! Form::open(['action' => 'App\Http\Controllers\SearchController@results', 'method' => 'POST']) !!}

    <div class="form-group" id="search_div">
        {{Form::label('search', 'Search')}}
        {{Form::text('search', '', ['class' => 'form-control', 'placeholder' => 'Search'])}}
    </div>

    <div class="form-group" id="searchfor_div">
        {{Form::label('searchfor', 'Search For')}}
        {{Form::select('searchfor', $options['searchfor'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group" id="platform_div">
        {{Form::label('platform', 'Platform')}}
        {{Form::select('platform', ['Any' => 'Any'] + $options['platform'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group" id="type_div">
        {{Form::label('type', 'Type')}}
        {{Form::select('type', ['Any' => 'Any'] + $options['type'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group" id="privacy_div">
        {{Form::label('privacy', 'Privacy')}}
        {{Form::select('privacy', ['Any' => 'Any'] + $options['privacy'], null, ['class' => 'form-control'])}}
    </div>

    {{Form::hidden('page', 0)}}

    <div class="text-center">
        {{Form::submit('Search', ['class' => 'btn btn-primary'])}}
    </div>

    {!! Form::close() !!}

@endsection