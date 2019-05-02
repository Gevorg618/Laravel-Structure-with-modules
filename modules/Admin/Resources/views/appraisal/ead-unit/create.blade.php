@extends('admin::layouts.master')

@section('title', 'EAD Business Units Manager')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Appraisal', 'url' => '#'],
  ['title' => 'EAD Business Units Manager', 'url' => route('admin.appraisal.ead-unit')],
  ['title' => 'New Business Unit', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-8">
            {{ Form::model($eadUnit, ['route' => [$eadUnit->id ? 'admin.appraisal.ead-unit.update' : 'admin.appraisal.ead-unit.create', 'id' => $eadUnit->id], 'class' => 'form-horizontal']) }}
            @if($eadUnit->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$eadUnit->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($eadUnit->id)?'Updating':'Creating' }}</h3>
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
                                <div class="col-md-6">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('title', old('title', $eadUnit->title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('unit_id', 'Unit ID', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('unit_id',old('unit_id',$eadUnit->unit_id), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('fha_lenderid', 'FHA Lender ID', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('fha_lenderid',old('fha_lenderid',$eadUnit->fha_lenderid), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('is_active', 'Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('is_active',[0 => 'No',1 => 'Yes'],old('category',$eadUnit->is_active), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('appr_type', 'Appraisal Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('appr_type[]',$eadUnit->appr_type_all,$eadUnit->appr_type->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('loan_type', 'Loan Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('loan_type[]',$eadUnit->loan_type_all,$eadUnit->loan_type->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('clients', 'Clients', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('clients[]',$eadUnit->clients_all,$eadUnit->clients->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('lenders', 'Lenders', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('lenders[]',$eadUnit->lenders_all,$eadUnit->lenders->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button class="btn btn-success pull-right">{{ ($eadUnit->id)?'Update':'Save' }}</button>
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
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.chosen').selectpicker();
    });
</script>
@endpush