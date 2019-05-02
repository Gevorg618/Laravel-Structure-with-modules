@extends('admin::layouts.master')

@section('title', 'New Ticket Status')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tickets', 'url' => '#'],
  ['title' => 'Ticket Statuses', 'url' => route('admin.ticket.statuses.index')],
  ['title' => 'New Ticket Status', 'url' => '']
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::open(['route' => 'admin.ticket.statuses.store', 'method' => 'post', 'class' => 'form-horizontal']) }}

            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Create</h3>
                </div>
                @include('admin::ticket.statuses.partials._form', ['button_label' => 'Submit'])
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop