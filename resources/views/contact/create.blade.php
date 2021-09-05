@extends('layouts.app')

@section('head')
    <script src="{{ asset('js/characterCount.js') }}" defer></script>
@endsection

@section('content')
    <h1>Contact Us</h1>
    
    {!! Form::open(['action' => 'App\Http\Controllers\ContactController@store', 'method' => 'POST']) !!}

    @guest
        <div class="form-group">
            {{Form::label('name', 'Name')}}
            {{Form::text('name', '', ['class' => 'form-control', 'placeholder' => ''])}}
        </div>

        <div class="form-group">
            {{Form::label('email', 'Email')}}
            {{Form::text('email', '', ['class' => 'form-control', 'placeholder' => ''])}}
        </div>
    @endguest

    <div class="form-group">
        <div class="d-flex">
            {{Form::label('message', 'Message')}}
            <div id='current-count' class='ml-auto text-secondary small'></div>
        </div>
        {{Form::textArea('message', '', ['class' => 'form-control input', 'placeholder' => 'Message'])}}
    </div>

    {{Form::submit('Send', ['class' => 'btn btn-primary'])}}
    {!! Form::close() !!}
@endsection