@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/hideQuestions.js') }}" defer></script>
@endsection

@section('content')
    <h1>Results</h1>
    <div class="card mt-3">
        <ul class="list-group list-group-flush">
            @if(count($results) > 0)
                @foreach($results as $result)
                    <li class="list-group-item">
                        @if (isset($result->creator_name))
                            <h3>Group Name: <a href="/group/{{str_replace(" ", "_", $result->name)}}/view">{{$result->name}}</a></h3>
                            <h5>Creator: <a href="/account/{{str_replace(" ", "_", $result->creator_name)}}/view">{{$result->creator_name}}</a></h5>
                            <p class="m-0">Privacy: {{$result->privacy}}</p>
                            <p class="m-0">Created on {{$result->created_at->format('M d, Y')}}</p>
                        @else
                            <h3>Name: <a href="/account/{{str_replace(" ", "_", $result->name)}}/view">{{$result->name}}</a></h3>
                            <p class="m-0">Created on {{$result->created_at->format('M d, Y')}}</p>
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

    <div class="text-center mt-2 d-flex w-100">
        @if ($request->page != 0)
        {!! Form::open(['action' => 'App\Http\Controllers\SearchController@results', 'method' => 'POST']) !!}
        {{Form::hidden('search', $request->search)}}
        {{Form::hidden('searchfor', $request->searchfor)}}
        {{Form::hidden('platform', $request->platform)}}
        {{Form::hidden('type', $request->type)}}
        {{Form::hidden('privacy', $request->privacy)}}
        {{Form::hidden('page', $request->page - 1)}}
        {{Form::submit('Last Page', ['class' => 'btn btn-primary m-2'])}}
        {!! Form::close() !!}   
        @endif

        @if (count($results) == 10)
        {!! Form::open(['action' => 'App\Http\Controllers\SearchController@results', 'method' => 'POST', 'class'=>'ml-auto']) !!}
        {{Form::hidden('search', $request->search)}}
        {{Form::hidden('searchfor', $request->searchfor)}}
        {{Form::hidden('platform', $request->platform)}}
        {{Form::hidden('type', $request->type)}}
        {{Form::hidden('privacy', $request->privacy)}}
        {{Form::hidden('page', $request->page + 1)}}
        {{Form::submit('Next Page', ['class' => 'btn btn-primary m-2'])}}
        {!! Form::close() !!}   
        @endif

    </div>
    
@endsection