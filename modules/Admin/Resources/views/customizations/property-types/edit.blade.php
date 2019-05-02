@extends('admin::layouts.master')

@section('title', 'Edit Property Type')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'Property Types', 'url' => route('admin.appraisal.property-types.index')],
  ['title' => 'New Property Type', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($propertyType, ['route' => ['admin.appraisal.property-types.update', $propertyType->id], 'method' => 'patch', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Create</h3>
                </div>
                @include('admin::customizations.property-types.partials.form', ['button_label' => 'Update'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop