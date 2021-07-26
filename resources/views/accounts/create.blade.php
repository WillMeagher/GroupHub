@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Account</div>

                <div class="card-body">
                    {!! Form::open(['action' => 'App\Http\Controllers\AccountsController@store', 'method' => 'POST']) !!}
                    
                    <div class="form-group">
                        {{Form::label('gender', 'Gender')}}
                        {{Form::select('gender', $options['gender'], null, ['placeholder' => 'Select your Gender', 'class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('school', 'School')}}
                        {{Form::select('school', $options['school'], null, ['placeholder' => 'Select your School', 'class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('major', 'Major')}}
                        {{Form::select('major', $options['major'], null, ['placeholder' => 'Select your Major', 'class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('year', 'Year')}}
                        {{Form::select('year', $options['year'], null, ['placeholder' => 'Select your Year', 'class' => 'form-control'])}}
                    </div>

                    {{Form::submit('Create Account', ['class' => 'btn btn-primary'])}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
