@extends('admin::layouts.master')


@section('title', $testimonial->id ? 'Update Client Testimonial' : 'Create Client Testimonial' )

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Client Testimonials',
            'url' => route('admin.frontend-site.client-testimonials.index')

        ],
        [
            'title' => $testimonial->id ? 'Update Client Testimonial' : 'Create Client Testimonial',
            'url' => $testimonial->id ? route('admin.frontend-site.client-testimonials.edit', ['id' => $testimonial->id]) : route('admin.frontend-site.client-testimonials.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $testimonial, ['route' => [$testimonial->id ? 'admin.frontend-site.client-testimonials.update' : 'admin.frontend-site.client-testimonials.create', 'id' => $testimonial->id], 'class' => 'form-horizontal', 'id' => 'clientTestimonialForm',  'enctype' => 'multipart/form-data']) }}
            @if($testimonial->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$testimonial->id ? 'Updating' : 'Creating'}}</h3>
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
                                    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                        {{ Form::label('name', 'Name', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('name', old('name'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) }}
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
                                            <button class="btn btn-success pull-right" type="submit">{{ $testimonial->id  ? 'Update' : 'Save' }}</button>
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
    <script src="{{masset('js/modules/admin/front-end/client_testimonial/crud.js')}}"></script>
@endpush