@extends('admin::layouts.master')create


@section('title', $serviceProvide->id ? 'Update Service' : 'Create Service')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Service We Provide',
            'url' => route('admin.frontend-site.services.index')

        ],
        [
            'title' => $serviceProvide->id ? 'Update Services' : 'Create Services',
            'url' => $serviceProvide->id ? route('admin.frontend-site.services.edit', ['id' => $serviceProvide->id]) : route('admin.frontend-site.services.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $serviceProvide, ['route' => [$serviceProvide->id ? 'admin.frontend-site.services.update' : 'admin.frontend-site.services.create', 'id' => $serviceProvide->id], 'class' => 'form-horizontal', 'id' => 'serviceWeProvideForm',  'enctype' => 'multipart/form-data']) }}
            @if($serviceProvide->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$serviceProvide->id ? 'Updating' : 'Creating'}}</h3>
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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }}">
                                        <div class="col-lg-5">
                                            <img src="{{$serviceProvide->id ? $serviceProvide->logo : ''}}" alt=""
                                                 id="logo_img_container" class="img-responsive">
                                        </div>
                                        <div class="col-lg-3 col-lg-offset-2 d-flex ">
                                            {{ Form::label('logo', 'Logo', ['class' => 'col-xs-12 required file-label required']) }}
                                        </div>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::file('logo', old('logo'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                                        {{ Form::label('icon', 'Icon', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('icon', old('icon'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                        {{ Form::label('description', 'Description', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right"
                                            type="submit">{{$serviceProvide->id ? 'Update' : 'Save'}}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('style')
    <link rel="stylesheet" href="{{ masset('css/front-end/index.css') }}">
@endpush
@push('scripts')
    <script src="{{masset('js/modules/admin/front-end/service-we-provide/crud.js')}}"></script>
@endpush