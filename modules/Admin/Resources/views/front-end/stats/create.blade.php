@extends('admin::layouts.master')


@section('title', $stat->id ? 'Update Stat Item' : 'Create Stat Item' )

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Front end Site', 'url' => '#'],
        [
            'title' => 'Stats',
            'url' => route('admin.frontend-site.stats.index')

        ],
        [
            'title' => $stat->id ? 'Update Stat Item' : 'Create Stat Item',
            'url' => $stat->id ? route('admin.frontend-site.stats.edit', ['id' => $stat->id]) : route('admin.frontend-site.stats.create')
        ]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            {{ Form::model( $stat, ['route' => [$stat->id ? 'admin.frontend-site.stats.update' : 'admin.frontend-site.stats.create', 'id' => $stat->id], 'class' => 'form-horizontal', 'id' => 'statForm',  'enctype' => 'multipart/form-data']) }}
            @if($stat->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{$stat->id ? 'Updating' : 'Creating'}}</h3>
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
                                    <div class="form-group{{ $errors->has('icon') ? ' has-error' : '' }}">
                                        {{ Form::label('icon', 'Icon', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('icon', old('icon'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="form-group{{ $errors->has('stat_number') ? ' has-error' : '' }}">
                                        {{ Form::label('stat_number', 'Stat Number', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('stat_number', old('stat_number'), ['class' => 'form-control', 'required' => 'required']) }}
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="ibox-footer">
                                            <button class="btn btn-success pull-right" type="submit">{{ $stat->id  ? 'Update' : 'Save' }}</button>
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
    <script>
        $(function () {
            $(document).on('submit', '#statForm', function () {
                $(this).find(':input[type=submit]')
                    .attr('disabled', true)
                    .removeClass('btn-success')
                    .addClass('btn-danger');
            })
        });
    </script>
@endpush