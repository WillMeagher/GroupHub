@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Edit Account') }}</div>

                <div class="card-body">
                    {!! Form::open(['action' => ['App\Http\Controllers\AccountsController@update', $user->id], 'method' => 'POST']) !!}
                    
                    <div class="form-group">
                        {{Form::label('gender', 'Gender')}}
                        {{Form::select('gender', [$user->gender => $user->gender] + $options['gender'], null, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('school', 'School')}}
                        {{Form::select('school', [$user->school => $user->school] + $options['school'], null, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('major', 'Major')}}
                        {{Form::select('major', [$user->major => $user->major] + $options['major'], null, ['class' => 'form-control'])}}
                    </div>

                    <div class="form-group">
                        {{Form::label('year', 'Year')}}
                        {{Form::select('year', [$user->year => $user->year] + $options['year'], null, ['class' => 'form-control'])}}
                    </div>

                    {{Form::hidden('_method', 'PUT')}}
                    {{Form::submit('Update Account', ['class' => 'btn btn-primary'])}}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
