@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <h4 class="card-header">{{ __('Account') }}</h4>
                <div class="card-body">
                    <h2>{{$user->name}}</h2>
                    <h4>User Id: {{$user->id}}</h4>
                    <h4>Email: {{$user->email}}</h4>
                    <h4>Gender: {{$user->gender}}</h4>
                    <a href="/account/edit" class="btn btn-default">Edit</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
