@extends('admin::layouts.master')

@section('title', 'Edit AMC')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tiger', 'url' => '#'],
  ['title' => 'AMCs', 'url' => route('admin.tiger.amcs.index')],
  ['title' => 'Update AMC', 'url' => '']
]
])
@endcomponent
@section('content')

    <div class="tabbable-line">
        <ul class="nav nav-tabs ">
            <li class="active">
                <a href="#info" data-toggle="tab">
                    Basic Info </a>
            </li>
            <li class="">
                <a href="#payment" data-toggle="tab">
                    Payment Info </a>
            </li>
            <li class="">
                <a href="#instructions" data-toggle="tab">
                    Instructions </a>
            </li>
        </ul>
        {{ Form::model($amc, ['route' => ['admin.tiger.amcs.update', $amc->id], 'method' => 'patch', 'class' => 'form-horizontal']) }}
        <div class="tab-content">
            <div class="tab-pane active" id="info">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Add Basic Info</h3>
                            </div>
                            @include('admin::tiger.amcs.partials._form_basic')
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane"  id="payment">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Add Credit Card Information</h3>
                            </div>
                            @include('admin::tiger.amcs.partials._form_payment_info')
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane"  id="instructions">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Instructions</h3>
                            </div>
                            @include('admin::tiger.amcs.partials._form_instructions')
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop