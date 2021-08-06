@extends('layouts.app')

@section('content')
    <h1>Results</h1>

    {!! Form::open(['action' => 'App\Http\Controllers\GroupsController@results', 'method' => 'POST']) !!}

    <div class="form-group">
        {{Form::label('search', 'Search')}}
        {{Form::text('search', $request->search, ['class' => 'form-control', 'placeholder' => 'Search'])}}
    </div>

    <div class="form-group">
        {{Form::label('platform', 'Platform')}}
        {{Form::select('platform', [$request->platform => $request->platform] + ['Any' => 'Any'] + $options['platform'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('type', 'Type')}}
        {{Form::select('type', [$request->type => $request->type] + ['Any' => 'Any'] + $options['type'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('privacy', 'Privacy')}}
        {{Form::select('privacy', [$request->privacy => $request->privacy] + ['Any' => 'Any'] + $options['privacy'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group">
        {{Form::label('sortby', 'Sort By')}}
        {{Form::select('sortby', [$request->sortby => $request->sortby] + $options['sortby'], null, ['class' => 'form-control'])}}
    </div>

    {{Form::submit('Search', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}

    @if(count($groups) > 0)
        <div class="card">
            <ul class="list-group list-group-flush">
            @foreach($groups as $group)
                <li class="list-group-item">
                    <h3><a href="/group/{{$group->id}}"> Name: {{$group->name}} Creator: {{$group->creator_name}}</a></h3>
                    <small>Created on {{$group->created_at}}</small>
                    <small>Score {{$group->score}}</small>
                </li>
            @endforeach
            </ul>
        </div>
    @endif
@endsection