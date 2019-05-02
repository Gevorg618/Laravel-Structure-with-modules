@extends('admin::layouts.master')

@section('title', 'Appraiser Groups')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraiser', 'url' => '#'],
      ['title' => 'Appraiser Groups', 'url' => route('admin.management.appraiser.index')],
      ['title' => 'New Appraiser Group', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"></h3>
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
                    <h3 style="color:cornflowerblue">Basic Information </h3>
                    {{ Form::open(['route' => 'admin.management.appraiser.store', 'class' => 'form-horizontal', 'id' => 'appraiser-group']) }}
                            <div class="form-body">
                                {{ csrf_field() }}
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label name="title" class="col-lg-3 col-xs-12 required">Title</label>
                                            <div class="col-lg-12 col-xs-12">
                                                <input type="text" name="title" class="form-control">
                                                <span class="help-block title-error-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label name="description" class="col-lg-3 col-xs-12 required">Description</label>
                                            <div class="col-lg-12 col-xs-12">
                                                <textarea name="description" class="form-control"></textarea>
                                                <span class="help-block description-error-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label name="manager" class="col-lg-3 col-xs-12 required">Manager</label>
                                            <div class="col-lg-12 col-xs-12">
                                                <input type="text" name="manager" class="form-control" id="autocomplete">
                                                <span class="help-block managerid-error-block"></span>
                                            </div>
                                        </div>
                                        <input type="hidden" id="managerid" name="managerid">
                                    </div>
                                </div>

                                <div class="row" style="margin-top: 50px;">
                                    <div class="ibox-footer">
                                        <button type="submit" class="btn btn-success pull-left">Save</button>
                                    </div>
                                </div>
                            </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    </div>
@stop
@push('scripts')
<script>
    var actions = <?php echo json_encode([
            'store'    => route('admin.management.appraiser.store'),
            'managers' => route('admin.management.appraiser.managers'),
            'index' => route('admin.management.appraiser.index'),
        ]); ?>
</script>
<script src="{{ masset('js/appraiser/autocomplete.js') }}"></script>
<script src="{{ masset('js/appraiser/validation.js') }}"></script>
@endpush