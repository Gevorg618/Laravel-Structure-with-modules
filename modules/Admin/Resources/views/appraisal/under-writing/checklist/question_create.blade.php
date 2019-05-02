@extends('admin::layouts.master')

@section('title', 'Appraisal UW Checklist')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraisal', 'url' => '#'],
      ['title' => 'Appraisal UW Checklist', 'url' => route('admin.appraisal.under-writing.checklist')],
      ['title' => 'New UW Question', 'url' => route('admin.appraisal.under-writing.checklist')]
    ],
    'actions' => [
      ['title' => 'Add Category', 'url' => route('admin.appraisal.under-writing.checklist.category.create')],
    ]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            {{ Form::model($question, ['route' => [$question->id ? 'admin.appraisal.under-writing.checklist.question.update' : 'admin.appraisal.under-writing.checklist.question.create', 'id' => $question->id], 'class' => 'form-horizontal']) }}
            @if($question->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$question->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($question->id)?'Updating':'Creating' }}</h3>
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
                        <div class="col-md-10 col-md-offset-1">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title', $question->title), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('correction', 'Correction', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('correction',$question->correction, ['class' => 'form-control','data-live-search' => 'true']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('category_id', 'Category', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('category_id',$categories,$selected_category, ['class' => 'form-control','data-live-search' => 'true']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('is_active', 'Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_active',['1' => 'Yes','0' => 'No'],$question->is_active, ['class' => 'form-control','data-live-search' => 'true']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('appr_type', 'Appraisal Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('appr_type[]',$question->appr_type_all,$question->appr_type->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('loan_type', 'Loan Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('loan_type[]',$question->loan_type_all,$question->loan_type->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('loan_reason', 'Loan Reason', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('loan_reason[]',$question->loan_reason_all,$question->loan_reason->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('clients', 'Clients', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('clients[]',$question->clients_all,$question->clients->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('lenders', 'Lenders', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('lenders[]',$question->lenders_all,$question->lenders->pluck('id')->all(), ['class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <button class="btn btn-success pull-right">{{ ($question->id)?'Update':'Save' }}</button>
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
    $('.multiselect').selectpicker();
</script>
@endpush