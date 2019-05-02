@extends('admin::layouts.master')

@section('title', 'Edit DocuVault Appraisal Type')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
    ['title' => 'Customizations', 'url' => '#'],
    ['title' => 'DocuVault Appraisal Types', 'url' => route('admin.docuvault.appraisal.index')],
    ['title' => 'Update DocuVailt Appraisal Type', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($appraisal, ['route' => ['admin.docuvault.appraisal.update', $appraisal->id], 'method' => 'patch', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Update</h3>
                </div>
                @include('admin::customizations.docu-vault-appraisal.partials._form', ['button_label' => 'Update'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop