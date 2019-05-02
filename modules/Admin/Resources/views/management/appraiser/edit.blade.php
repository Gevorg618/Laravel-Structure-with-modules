@extends('admin::layouts.master')

@section('title', 'Appraiser Groups')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraiser', 'url' => '#'],
      ['title' => 'Appraiser Groups', 'url' => route('admin.management.appraiser.index')],
      ['title' => 'Edit Appraiser Group', 'url' => '#']
    ],
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
                    <h3 style="color:cornflowerblue">Basic Informations </h3>
                    {{ Form::model($group, ['route' => ['admin.management.appraiser.update', $group->id], 'method' => 'put', 'class' => 'form-horizontal', 'id' => 'appraiser-group']) }}
                    <div class="form-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label name="title" class="col-lg-3 col-xs-12 required">Title</label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text('title', $group->title, ['class' => 'form-control']) !!}
                                        <span class="help-block title-error-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label name="description" class="col-lg-3 col-xs-12 required">Description</label>
                                    <div class="col-lg-12 col-xs-12">
                                        {{ Form::textarea('description', $group->title, ['class' => 'form-control']) }}
                                        <span class="help-block description-error-block"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label name="manager" class="col-lg-3 col-xs-12 required">Manager</label>
                                    <div class="col-lg-12 col-xs-12">
                                        {!! Form::text(
                                            'manager',
                                            $group->manager->userData->firstname.' '.$group->manager->userData->lastname.' '.'( '.$group->manager->email.' )',
                                            ['class' => 'form-control', 'id' => 'autocomplete'])
                                        !!}
                                        <span class="help-block managerid-error-block"></span>
                                    </div>
                                </div>
                                {!! Form::hidden('managerid', $group->managerid, ['class' => 'form-control', 'id' => 'managerid']) !!}
                            </div>
                        </div>

                        <div class="row" style="margin-top: 50px;">
                            <div class="ibox-footer">
                                <button type="submit" class="btn btn-success pull-left">Edit</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-lg-6">

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"></h3>
                </div>
                <div class="panel-body">
                    <h3 style="color:cornflowerblue">Appraisers in Group</h3>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    {!! Form::text('appraisers','',['class' => 'tm-input form-control tm-input-info', 'id' => 'appraisers'])!!}
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-xs-12">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th>User</th>
                                        <th style="width:1px;">Remove</th>
                                    </tr>
                                    </thead>
                                    <tbody id="appraiser-table">
                                    @foreach($group->appraisers as $appraiser)
                                        <tr id="tr-{{ $appraiser->id }}">
                                            <td>{{ $appraiser->userData->firstname }} {{ $appraiser->userData->lastname }}<small>({{ $appraiser->email }})</small>
                                            </td>
                                            <td style="text-align: center"><i data-id="{{$appraiser->id}}"
                                                                              class="fa fa-remove delete-appraiser"
                                                                              style="cursor: pointer; text-align: center;"></i>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if($group->appraisers->isEmpty())
                                        <tr id="empty-appraisers">
                                            <td colspan="2">
                                                no records
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@stop
@push('scripts')
<script>
    var actions = <?php echo json_encode([
        'managers'          => route('admin.management.appraiser.managers'),
        'appraisers'        => route('admin.management.appraiser.appraisers'),
        'storeAppraisers'   => route('admin.management.appraiser.appraisers.store'),
        'destroyAppraiser'  => route('admin.management.appraiser.appraisers.destroy'),
        'index'             => route('admin.management.appraiser.index'),
        'groupId'           => $group->id,
    ]); ?>
</script>
<script src="{{ masset('js/appraiser/autocomplete.js') }}"></script>
<script src="{{ masset('js/appraiser/edit.js') }}"></script>
<script src="{{ masset('js/appraiser/validation.js') }}"></script>
@endpush