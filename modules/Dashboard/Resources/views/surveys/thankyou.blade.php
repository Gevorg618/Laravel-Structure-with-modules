@extends('dashboard::layouts.master')

@section('title', 'Surveys')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h2>{{ $survey->title }}</h2>
        </div>
        <div class="col-lg-6">
            Thank you for submitting survey
        </div>
    </div>
@stop

