@extends('admin::layouts.master')

@section('title', 'Edit User Group')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'User Groups', 'url' => route('admin.management.groups.index')],
  ['title' => 'Update User Group', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($group, ['route' => ['admin.management.groups.update', $group->id], 'method' => 'patch', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Update</h3>
                </div>
                @include('admin::management.groups.partials._form', ['button_label' => 'Update'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop