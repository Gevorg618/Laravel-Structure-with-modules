@extends('admin::layouts.master')

@section('title', 'Custom Email Templates')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Management', 'url' => '#'],
      ['title' => 'Custom Email Templates', 'url' => route('admin.management.custom-email-templates')],
      ['title' => 'New Custom Email Template', 'url' => '']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-8">
            {{ Form::model($customEmailTemplate, ['route' => [$customEmailTemplate->id ? 'admin.management.custom-email-templates.update' : 'admin.management.custom-email-templates.create', 'id' => $customEmailTemplate->id], 'class' => 'form-horizontal']) }}
            @if($customEmailTemplate->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$customEmailTemplate->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($customEmailTemplate->id)?'Updating':'Creating' }}</h3>
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
                                            {{ Form::label('title', 'Template Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('title', old('title', $customEmailTemplate->title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('email_key', 'Template Key', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('email_key',old('email_key',$customEmailTemplate->email_key), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('software_fee', 'Software Fee', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('software_fee',['0' => 'No','1' => 'Yes'],old('category',$customEmailTemplate->software_fee), ['class' => 'form-control multiselect']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('appr_type', 'Appraisal Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('appr_type[]',$customEmailTemplate->appr_type_all,$customEmailTemplate->appr_type->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('loan_type', 'Loan Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('loan_type[]',$customEmailTemplate->loan_type_all,$customEmailTemplate->loan_type->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('states', 'States', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('states[]',$customEmailTemplate->states_all,$customEmailTemplate->states->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('clients', 'Clients', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('clients[]',$customEmailTemplate->clients_all,$customEmailTemplate->clients->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('lenders', 'Lenders', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('lenders[]',$customEmailTemplate->lenders_all,$customEmailTemplate->lenders->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('content', 'Content', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('content', old('content', $customEmailTemplate->content), ['class' => 'form-control summernote']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($customEmailTemplate->id)?'Update':'Save' }}</button>
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
@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
        $('.multiselect').selectpicker();
    });
</script>
@endpush