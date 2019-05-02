@extends('admin::layouts.master')


@section('title', $member->id ? 'Update Team Member' : 'Create Team Member' )

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Team Member',
            'url' => route('admin.frontend-site.team-member.index')

        ],
        [
            'title' => $member->id ? 'Update Team Member' : 'Create Team Member',
            'url' => $member->id ? route('admin.frontend-site.team-member.edit', ['id' => $member->id]) : route('admin.frontend-site.team-member.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $member, ['route' => [$member->id ? 'admin.frontend-site.team-member.update' : 'admin.frontend-site.team-member.create', 'id' => $member->id], 'class' => 'form-horizontal', 'id' => 'teamMemberForm',  'enctype' => 'multipart/form-data']) }}
            @if($member->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$member->id ? 'Updating' : 'Creating'}}</h3>
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
                                            <img src="{{$member->id ? $member->image : ''}}" alt=""
                                                 id="image_img_container" class="img-responsive">
                                        </div>
                                        <div class="col-lg-3 col-lg-offset-2 d-flex ">
                                            {{ Form::label('image', 'Image', ['class' => 'col-xs-12 required file-label', 'required' => 'required']) }}
                                        </div>
                                        <div class="col-md-12">
                                            <span class="help">Required Dimensions: Width: 400px, Height: 427px</span>
                                        </div>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::file('image', old('image'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
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

                                    <div class="form-group{{ $errors->has('social_links') ? ' has-error' : '' }}">
                                        {{ Form::label('social_links', 'Social Links', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            <button type="button" class="btn btn-primary m " data-type="add_socialLink">Add
                                                Button
                                            </button>
                                            <div class="social_link_block">
                                                @if($member->social_links)
                                                    @foreach($member->social_links as $icon => $url)
                                                        <div class="row" data-dom-id={{$loop->iteration}}>
                                                            <div class="col-md-5">
                                                                {{ Form::label('social_icon['.$loop->iteration.']', 'Icon', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                                                {{ Form::text('social_icon['.$loop->iteration.']', $icon, ['class' => 'form-control m', 'required' => 'required']) }}

                                                            </div>
                                                            <div class="col-md-5">
                                                                {{ Form::label('social_url['.$loop->iteration.']', 'Url', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                                                {{ Form::text('social_url['.$loop->iteration.']', $url, ['class' => 'form-control m', 'required' => 'required']) }}
                                                            </div>
                                                            <div class="col-md-1">
                                                                <button type="button" class="btn btn-danger m"
                                                                        data-predestination="delete_social_link"
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
                                            <button class="btn btn-success pull-right" type="submit">{{ $member->id  ? 'Update' : 'Save' }}</button>
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
    <script src="{{masset('js/modules/admin/front-end/team-member/crud.js')}}"></script>
@endpush