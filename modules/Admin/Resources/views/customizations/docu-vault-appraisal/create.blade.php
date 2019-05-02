@extends('admin::layouts.master')

@section('title', 'New DocuVault Appraisal Type')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'DocuVault Appraisal Types', 'url' => route('admin.docuvault.appraisal.index')],
  ['title' => 'New DocuVailt Appraisal Type', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::open(['route' => 'admin.docuvault.appraisal.store', 'method' => 'post', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Create</h3>
                </div>
                @include('admin::customizations.docu-vault-appraisal.partials._form', ['button_label' => 'Submit'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop