@extends('admin::layouts.master')


@section('title', $carousel->id ? 'Update Header Carousel' : 'Create Header Carousel' )

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Header Carousel',
            'url' => route('admin.frontend-site.header-carousel.index')

        ],
        [
            'title' => $carousel->id ? 'Update Header Carousel' : 'Create Header Carousel',
            'url' => $carousel->id ? route('admin.frontend-site.header-carousel.edit', ['id' => $carousel->id]) : route('admin.frontend-site.header-carousel.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $carousel, ['route' => [$carousel->id ? 'admin.frontend-site.header-carousel.update' : 'admin.frontend-site.header-carousel.create', 'id' => $carousel->id], 'class' => 'form-horizontal', 'id' => 'headerCarouselForm',  'enctype' => 'multipart/form-data']) }}
            @if($carousel->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$carousel->id ? 'Updating' : 'Creating'}}</h3>
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
                                    <div class="form-group{{ $errors->has('desktop_image') ? ' has-error' : '' }}">
                                        <div class="col-lg-5">
                                            <img src="{{$carousel->id ? $carousel->desktop_image : ''}}" alt=""
                                                 id="desktop_img_container" class="img-responsive">
                                        </div>
                                        <div class="col-lg-3 col-lg-offset-2 d-flex ">
                                            {{ Form::label('desktop_image', 'Desktop Image', ['class' => 'col-xs-12 required file-label', 'required' => 'required']) }}
                                        </div>
                                        <div class="col-md-12">
                                            <span class="help">Required Dimensions: Width: 1920px, Height: 1080px</span>
                                        </div>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::file('desktop_image', old('desktop_image'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('mobile_image') ? ' has-error' : '' }}">
                                        <div class="col-lg-5">
                                            <img src="{{$carousel->id ? $carousel->mobile_image : ''}}" alt=""
                                                 id="mobile_img_container" class="img-responsive">
                                        </div>
                                        <div class="col-lg-3 col-lg-offset-2 d-flex ">
                                            {{ Form::label('mobile_image', 'Mobile Image', ['class' => 'col-xs-12 required file-label']) }}
                                        </div>
                                        <div class="col-md-12">
                                            <span class="help">Required Dimensions: Width: 960px, Height: 560px</span>
                                        </div>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::file('mobile_image', old('mobile_image'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                        {{ Form::label('description', 'Description', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('description', old('description'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
                                        {{ Form::label('position', 'Position', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('position', old('position'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', 'Is Active', ['class' => 'col-lg-12 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_active', [ 0 => 'No', 1 => 'Yes'], old('is_active'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('buttons') ? ' has-error' : '' }}">
                                        {{ Form::label('buttons', 'Buttons', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="button" class="btn btn-primary m " data-type="add_button">Add
                                                Button
                                            </button>
                                            <div class="buttons_block">
                                                @if($carousel->buttons)
                                                    @foreach($carousel->buttons as $title => $url)
                                                        <div class="row" data-dom-id={{$loop->iteration}}>
                                                            <div class="col-md-5">
                                                                {{ Form::label('buttons_title['.$loop->iteration.']', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                                                {{ Form::text('buttons_title['.$loop->iteration.']', $title, ['class' => 'form-control m', 'required' => 'required']) }}

                                                            </div>
                                                            <div class="col-md-5">
                                                                {{ Form::label('buttons_link['.$loop->iteration.']', 'Link', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                                                {{ Form::text('buttons_link['.$loop->iteration.']', $url, ['class' => 'form-control m', 'required' => 'required']) }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn-danger m"
                                                                        data-predestination="delete_button"
                                                                        data-id="{{$loop->iteration}}">Delete
                                                                </button>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ibox-footer">
                                            <button class="btn btn-success pull-right" type="submit">{{ $carousel->id  ? 'Update' : 'Save' }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
@push('style')
    <link rel="stylesheet" href="{{ masset('/css/front-end/index.css') }}">

@endpush
@push('scripts')
    <script src="{{masset('js/modules/admin/front-end/header-carousel/crud.js')}}"></script>
@endpush