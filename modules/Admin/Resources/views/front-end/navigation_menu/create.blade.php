@extends('admin::layouts.master')


@section('title', $navigationMenu->id ? 'Update Navigation Menu' : 'Create Navigation Menu')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Navigation Menu',
            'url' => route('admin.frontend-site.navigation-menu.index')

        ],
        [
            'title' => $navigationMenu->id ? 'Update Navigation Menu' : 'Create Navigation Menu',
            'url' => $navigationMenu->id ? route('admin.frontend-site.navigation-menu.edit', ['id' => $navigationMenu->id]) : route('admin.frontend-site.navigation-menu.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $navigationMenu, ['route' => [$navigationMenu->id ? 'admin.frontend-site.navigation-menu.update' : 'admin.frontend-site.navigation-menu.create', 'id' => $navigationMenu->id], 'class' => 'form-horizontal', 'id' => 'navigationMenuForm',  'enctype' => 'multipart/form-data']) }}
            @if($navigationMenu->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$navigationMenu ? 'Updating' : 'Creating'}}</h3>
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
                                    <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
                                        {{ Form::label('url', 'Url', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('url', old('url'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                                        {{ Form::label('slug', 'Slug', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('slug', old('slug'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('is_active') ? ' has-error' : '' }}">
                                        {{ Form::label('is_active', 'Is Active', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_active',[1 => 'Yes', 0 => 'No'], old('is_active'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('is_quick_link') ? ' has-error' : '' }}">
                                        {{ Form::label('is_quick_link', 'Is Quick Links', ['class' => 'col-lg-3 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_quick_link',[ 0 => 'No' , 1 => 'Yes'], old('is_active'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('childes') ? ' has-error' : '' }}">
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::label('childes', 'Childes', ['class' => 'col-lg-3 col-xs-12']) }}
                                            <div class="col-md-offset-5 col-md-2">
                                                <h3>Is Drop Down</h3>
                                            </div>
                                            <div class="col-xs-6 col-md-2">
                                                <div id="toggles">
                                                    <input type="checkbox" name="is_drop_down" id="is_drop_down"
                                                           class="ios-toggle"
                                                           value="1" {{$navigationMenu->is_drop_down ? 'checked="checked"' : ''}}/>
                                                    <label for="is_drop_down" class="checkbox-label" data-off="No"
                                                           data-on="Yes"></label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-xs-12 childes_section {{$navigationMenu->is_drop_down ? '' : 'ds_none'}}">
                                                <button type="button" class="btn btn-primary m " data-type="add_child">
                                                    Add Child
                                                </button>
                                                <div class="childes_block">
                                                    @if($navigationMenu->childes)
                                                        @foreach($navigationMenu->childes as $title => $url)
                                                            <div class="row" data-dom-id="{{$loop->iteration}}">
                                                                <div class="col-md-5">
                                                                    {{ Form::label('child_title['.$loop->iteration.']', 'Child Title', ['class' => 'col-lg-6 col-xs-12 required']) }}
                                                                    {{ Form::text('child_title['.$loop->iteration.']', $title, ['class' => 'form-control m', 'required' => 'required']) }}
                                                                </div>
                                                                <div class="col-md-5">
                                                                    {{ Form::label('child_url['.$loop->iteration.']', 'Child Url', ['class' => 'col-lg-6 col-xs-12 required']) }}
                                                                    {{ Form::text('child_url['.$loop->iteration.']', $title, ['class' => 'form-control m', 'required' => 'required']) }}
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <button type="button" class="btn btn-danger m"
                                                                            data-predestination="delete_child"
                                                                            data-id="{{$loop->iteration}}">Delete
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    @endif
                                                </div>
                                                @if ($errors->has('childes'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('childes') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ibox-footer">
                                            <button class="btn btn-success pull-right"
                                                    type="submit">{{ $navigationMenu->id  ? 'Update' : 'Save' }}</button>
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
    <link rel="stylesheet" href="{{masset('css/front-end/navigation_menu/index.css')}}">
@endpush
@push('scripts')
    <script src="{{masset('js/modules/admin/front-end/navigation-menu/crud.js')}}"></script>
@endpush