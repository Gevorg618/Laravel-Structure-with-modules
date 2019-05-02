@extends('admin::layouts.master')

@section('title', 'Edit Ticket Status')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tickets', 'url' => '#'],
  ['title' => 'Ticket Statuses', 'url' => route('admin.ticket.statuses.index')],
  ['title' => 'Update Ticket Status', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($status, ['route' => ['admin.ticket.statuses.update', $status->id], 'method' => 'patch', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Update</h3>
                </div>
                @include('admin::ticket.statuses.partials._form', ['button_label' => 'Update'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop