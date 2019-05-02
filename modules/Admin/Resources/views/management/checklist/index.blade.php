@extends('admin::layouts.master')

@section('title', 'Appraisal QC Checklist')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'QC', 'url' => '#'],
        ['title' => 'Checklist', 'url' => route('admin.qc.checklist.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="pull-right">
                            <a href="{{ route('admin.qc.checklist.create_category') }}"
                               class="btn btn-sm btn-primary">Add Category</a>
                            <a href="{{ route('admin.qc.checklist.create') }}"
                               class="btn btn-sm btn-primary">Add Question</a>
                        </div>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <ul class="nav nav-tabs" id="checklist_tab">
                                <li class="active"><a href="#general" data-toggle="tab">General Checklist</a></li>
                                <li><a href="#client" data-toggle="tabajax" data-url="{!! route('admin.qc.checklist.clients_data') !!}">Client Specific Checklist</a></li>
                                <li><a href="#lender" data-toggle="tabajax" data-url="{!! route('admin.qc.checklist.lenders_data') !!}">Lender Specific Checklist</a></li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general">
                                    <br />
                                    <div id="sortable">
                                        @if($cats)
                                            @foreach($cats as $row)
                                                @include('management.checklist.partials.category_row')
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <div class="tab-pane" id="client">
                                    <br />

                                </div>

                                <div class="tab-pane" id="lender">
                                    <br />

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
