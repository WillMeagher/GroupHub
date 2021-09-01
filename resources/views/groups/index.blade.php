@extends('layouts.app')

@section('content')
    <h1>{{isset($title) ? $title : 'Groups'}}</h1>
    <div class="card mt-3">
        <ul class="list-group list-group-flush">
            @if(count($groups) > 0)
                @foreach($groups as $group)
                    <li class="list-group-item">
                        <h3>Name: <a href="/group/{{str_replace(" ", "_", $group->name)}}/view">{{$group->name}}</a></h3>
                        <h5>Creator: <a href="/account/{{str_replace(" ", "_", $group->creator_name)}}/view">{{$group->creator_name}}</a></h5>
                        <small>Created on {{$group->created_at->format('M d, Y')}}</small>
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