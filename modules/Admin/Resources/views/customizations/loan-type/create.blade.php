@extends('admin::layouts.master')


@section('title', $loantype->id ? 'Updating Loan Type' : 'New Loan Type')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
   ['title' => 'Customizations', 'url' => '#'],
   ['title' => 'Loan Type', 'url' => route('admin.appraisal.loantype')],
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($loantype, ['route' => [$loantype->id ? 'admin.appraisal.loantype.update' : 'admin.appraisal.loantype.create', 'id' => $loantype->id], 'class' => 'form-horizontal']) }}
            @if($loantype->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('id', old('id', $loantype->id), ['class' => 'form-control']) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($loantype->id)?'Updating':'Creating' }}</h3>
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
                                    <div class="form-group">
                                        {{ Form::label('descrip', 'Description', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('descrip', old('descrip', $loantype->descrip), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('mismo_label', 'Mismo Label', ['class' => 'col-lg-3 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('mismo_label', old('mismo_label', $loantype->mismo_label), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('is_default', 'Is Default', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_default', [1 => 'Yes',0 =>'No',],old('is_default', $loantype->is_default), ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($loantype->id)?'Update':'Save' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop