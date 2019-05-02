@extends('admin::layouts.master')

@section('title', 'Mercury Network')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Integrations', 'url' => '#'],
        ['title' => 'Mercury Network', 'url' => route('admin.integrations.mercury')]
    ]
])
@endcomponent

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Edit Record</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{ Form::model($edge, [ 'method'=>'POST', 'route' => ['admin.integrations.equity-edge-update', $edge->id], 'class' => 'form-group' ])}}
                       @include('integrations.mercury_network.equity-edge.partials._form')
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
