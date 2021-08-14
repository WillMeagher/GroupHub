@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h4 class="card-header">{{ __('Account') }}</h4>
                <div class="card-body">
                    <h2>{{$user->name}}</h2>
                    <h4>Email: {{$user->email}}</h4>
                    <h4>Gender: {{$user->gender}}</h4>
                    <h4>School: {{$user->school}}</h4>
                    <h4>Year: {{$user->year}}</h4>
                    <h4>Major: {{$user->major}}</h4>
                    
                    @if (Auth::user()->id == $user->id)
                        <a href="/account/{{str_replace(" ", "_", $user->name)}}/edit" class="btn btn-default border-dark">Edit</a>
                    @endif
                    <a href="/account/{{str_replace(" ", "_", $user->name)}}/joined" class="btn btn-default border-dark">Joined Groups</a>
                    <a href="/account/{{str_replace(" ", "_", $user->name)}}/created" class="btn btn-default border-dark">Created Groups</a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
