@extends('layouts.app')

@section('content')
    <div class="card">
        <h4 class="card-header">{{ __('Edit Account') }}</h4>

        <div class="card-body">
            {!! Form::open(['action' => ['App\Http\Controllers\AccountsController@update', $user->name], 'method' => 'POST']) !!}
            
            <div class="form-group">
                {{Form::label('gender', 'Gender')}}
                {{Form::select('gender', [$user->gender => $user->gender] + $options['gender'], null, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
                {{Form::label('major', 'Major')}}
                {{Form::select('major', [$user->major => $user->major] + $options['major'], null, ['class' => 'form-control'])}}
            </div>

            <div class="form-group">
                {{Form::label('year', 'Year')}}
                {{Form::select('year', [$user->year => $user->year] + $options['year'], null, ['class' => 'form-control'])}}
            </div>

            <div class="d-flex">
                {{Form::hidden('_method', 'PUT')}}
                {{Form::submit('Update Account', ['class' => 'btn btn-primary'])}}
                {!! Form::close() !!}

                {!!Form::open(['action'=>['App\Http\Controllers\AccountsController@destroy', $user->name], 'method' => 'POST', 'class' => 'ml-auto'])!!}
                {{Form::hidden('_method', 'DELETE')}}
                {{Form::submit('Delete Account', ['class' => 'btn btn-danger'])}}
                {!!Form::close()!!}
            </div>
        </div>
    </div>
@endsection
