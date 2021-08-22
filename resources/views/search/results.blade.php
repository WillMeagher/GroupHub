@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/hideQuestions.js') }}" defer></script>
@endsection

@section('content')
    <h1>Results</h1>

    {!! Form::open(['action' => 'App\Http\Controllers\SearchController@results', 'method' => 'POST']) !!}

    <div class="form-group" id="search_div">
        {{Form::label('search', 'Search')}}
        {{Form::text('search', $request->search, ['class' => 'form-control', 'placeholder' => 'Search'])}}
    </div>

    <div class="form-group" id="searchfor_div">
        {{Form::label('searchfor', 'Search For')}}
        {{Form::select('searchfor', [$request->searchfor => $request->searchfor] + $options['searchfor'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group" id="platform_div">
        {{Form::label('platform', 'Platform')}}
        {{Form::select('platform', [$request->platform => $request->platform] + ['Any' => 'Any'] + $options['platform'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group" id="type_div">
        {{Form::label('type', 'Type')}}
        {{Form::select('type', [$request->type => $request->type] + ['Any' => 'Any'] + $options['type'], null, ['class' => 'form-control'])}}
    </div>

    <div class="form-group" id="privacy_div">
        {{Form::label('privacy', 'Privacy')}}
        {{Form::select('privacy', [$request->privacy => $request->privacy] + ['Any' => 'Any'] + $options['privacy'], null, ['class' => 'form-control'])}}
    </div>

    <div class="text-center">
        {{Form::submit('Search', ['class' => 'btn btn-primary'])}}
    </div>

    {!! Form::close() !!}

    <div class="card mt-3">
        <ul class="list-group list-group-flush">
            @if(count($results) > 0)
                @foreach($results as $result)
                    <li class="list-group-item">
                        @if (isset($result->creator_name))
                            <h3>Group Name: <a href="/group/{{str_replace(" ", "_", $result->name)}}">{{$result->name}}</a></h3>
                            <h5>Creator: <a href="/account/{{str_replace(" ", "_", $result->creator_name)}}">{{$result->creator_name}}</a></h5>
                            <p class="m-0">Privacy: {{$result->privacy}}</p>
                            <p class="m-0">Created on {{$result->created_at->format('M d, Y')}}</p>
                            <p class="m-0">Score: {{$result->score}}</p>                            
                        @else
                            <h3>Name: <a href="/account/{{str_replace(" ", "_", $result->name)}}">{{$result->name}}</a></h3>
                            <p class="m-0">Created on {{$result->created_at->format('M d, Y')}}</p>
                            <p class="m-0">Score {{$result->score}}</p>
                        @endif
                    </li>
                @endforeach
            @else
                <li class="list-group-item">
                    <h3 class="m-2">No Results</h3>
                </li>
            @endif
       </ul>
    </div>
@endsection