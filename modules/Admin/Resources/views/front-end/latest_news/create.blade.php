@extends('admin::layouts.master')

@section('title', $latestNews->id ? 'Update Latest News' : 'Create Latest News' )

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Latest News',
            'url' => route('admin.frontend-site.latest-news.index')

        ],
        [
            'title' => $latestNews->id ? 'Update Latest News' : 'Create Latest News',
            'url' => $latestNews->id ? route('admin.frontend-site.latest-news.edit', ['id' => $latestNews->id]) : route('admin.frontend-site.latest-news.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $latestNews, ['route' => [$latestNews->id ? 'admin.frontend-site.latest-news.update' : 'admin.frontend-site.latest-news.create', 'id' => $latestNews->id], 'class' => 'form-horizontal', 'id' => 'latestNewForm',  'enctype' => 'multipart/form-data']) }}
            @if($latestNews->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$latestNews->id ? 'Updating' : 'Creating'}}</h3>
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
                                    <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
                                        <div class="col-lg-5">
                                            <img src="{{$latestNews->id ? $latestNews->image : ''}}" alt=""
                                                 id="image_img_container" class="img-responsive">
                                        </div>
                                        <div class="col-lg-3 col-lg-offset-2 d-flex ">
                                            {{ Form::label('image', 'Image', ['class' => 'col-xs-12 required file-label']) }}
                                        </div>
                                        <div class="col-md-12">
                                            <span class="help">Required Dimensions: Width: 612px, Height: 408px</span>
                                        </div>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::file('image', ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('short_description') ? ' has-error' : '' }}">
                                        {{ Form::label('short_description', 'Short Description', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('short_description', old('short_description'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', 'Is Active', ['class' => 'col-lg-12 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_active', [ 0 => 'No', 1 => 'Yes'], old('is_active'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('content') ? ' has-error' : '' }}">
                                        {{ Form::label('content', 'Content', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('content', old('content'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="ibox-footer">
                                            <button class="btn btn-success pull-right"
                                                    type="submit">{{ $latestNews->id  ? 'Update' : 'Save' }}</button>
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
    <link rel="stylesheet" href="{{ masset('css/frontend/header_carousel/index.css') }}">
@endpush
@push('scripts')
    <script src="{{masset('js/modules/admin/front-end/latest-news/crud.js')}}"></script>
@endpush