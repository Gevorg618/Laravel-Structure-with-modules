@extends('admin::layouts.login')

@section('content')
<div class="row">
  <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-6 col-sm-offset-3 text-center">
      <img src='https://landmarknetwork.com/timthumb.php?src=https%3A%2F%2Flandmarknetwork.com%2Fuploads%2Flogos%2Flandmark-small-logo-new.jpg&w=180' />
      <div class="hr-line-dashed"></div>
  </div>
</div>
<div class="row">
  <div class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-6 col-sm-offset-3">
    <div class="ibox-content">
        {{ Form::open(['route' => 'admin.login', 'class' => 'm-t']) }}
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
                {{ Form::text('email', old('email'), ['class' => 'form-control', 'placeholder' => 'Email Address']) }}    
            </div>
            <div class="form-group">
                {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>
        {{ Form::close() }}
    </div>
  </div>
</div>
@stop