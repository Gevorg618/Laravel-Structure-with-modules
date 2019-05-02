@extends('admin::layouts.master')

@section('back_button')
    <a href="{{route('admin.management.admin-groups')}}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
    </a>
@endsection

@section('title', 'Adding New Admin Group')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Admin Groups', 'url' => route('admin.management.admin-groups')],
        ['title' => 'Adding New Admin Group', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form method="POST" class="form-horizontal" action="{{route('admin.management.admin-groups.store')}}">
                            {{ csrf_field() }}
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="tabbable tabs-left">
                                            <ul class="nav nav-tabs">
                                                <li><a href="#info" data-toggle="tab">Basic Info</a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="info">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h4 class="text-info">Basic Information</h4>
                                                            <div class="form-group">
                                                                <label for="title" class="required">Title</label>
                                                                <div>
                                                                    <input class="form-control" type="text" name="title" id="title" required="">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="color">Color</label>
                                                                <div>
                                                                    <input class="form-control" type="text" name="color" id="color">
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="style">Style</label>
                                                                <div>
                                                                    <input class="form-control" type="text" name="style" id="style">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-offset-1 col-md-10">
                                        <button type="submit" id="submit" class="btn btn-primary save_button">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <link href="{{ masset('css/management/admin_groups/create_edit.css') }}" rel="stylesheet" />
@endpush
