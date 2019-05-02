@extends('admin::layouts.login')

@section('content')
    <div class="row">
    </div>
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-6 col-sm-offset-3">
            <div class="ibox-content">
                {{ Form::open(['route' => 'protect','method'=>'GET', 'class' => 'm-t']) }}
                @if (isset($errors) AND $errors->all())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            {{ $error }}<br>
                        @endforeach
                    </div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning">
                        {!! session('warning') !!}
                    </div>
                @endif
                <div class="form-group">
                    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}
                    <span class="staylogged">You will stay logged in for 1 day</span>
                </div>
                <button type="submit" class="btn btn-primary block full-width m-b">Continue</button>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@stop