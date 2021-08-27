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
@endsection