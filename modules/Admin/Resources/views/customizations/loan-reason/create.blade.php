@extends('admin::layouts.master')

@section('title', 'Customizations')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Loan Reasons', 'url' => route('admin.appraisal.loanreason')],
  ['title' => 'New Loan Reason', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($loanreason, ['route' => [$loanreason->id ? 'admin.appraisal.loanreason.update' : 'admin.appraisal.loanreason.create', 'id' => $loanreason->id], 'class' => 'form-horizontal']) }}
            @if($loanreason->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($loanreason->id)?'Updating':'Creating' }}</h3>
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
                                            {{ Form::text('descrip', old('descrip', $loanreason->descrip), ['class' => 'form-control']) }}
                                        </div>
                                        {{ Form::label('mismo_label', 'Mismo', ['class' => 'col-lg-3 col-xs-12']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('mismo_label', old('mismo_label', $loanreason->mismo_lavel), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($loanreason->id)?'Update':'Save' }}</button>
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