@extends('admin::layouts.master')

@section('title', 'Purchase & New Construction Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Appraisal Pipeline', 'url' => '#'],
        ['title' => 'Purchase & New Construction Pipeline', 'url' => route('admin.appraisal-pipeline.purchase-pipeline')]
        ]
        ])
        @endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="is_rush">Rush</label>
                                    <select class='form-control filter-change' id="is_rush" name="is_rush">
                                        <option value="">All</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="escalated">Escalated</label>
                                    <select class='form-control filter-change' id="escalated" name="escalated">
                                        <option value="">All</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="team">Team</label>
                                    <select class='form-control filter-change' id="team" name="team">
                                        <option value="">All</option>
                                        @foreach($teams as $team)
                                            <option value="{{$team->id}}">{{$team->team_title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="pending_review">Pending Review</label>
                                    <select class='form-control filter-change' id="pending_review" name="pending_review">
                                        <option value="">All</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class='form-control filter-change' id="status" name="status">
                                        <option value="">All</option>
                                        @foreach($statuses as $status)
                                            <option value="{{$status->id}}">{{$status->descrip}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="client">Client</label>
                                    <select class='form-control filter-change' id="client" name="client">
                                        <option value="">All</option>
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->descrip}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="due_date">Due Date</label>
                                    <input type="text" name="due_date" id="due_date" class='form-control date-selector filter-change' value=""/>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quick_filter">Quick Filter</label>
                                    <select class='form-control filter-change' id="quick_filter" name="quick_filter">
                                        <option value="">All Orders</option>
                                        <option value="today">Due Today</option>
                                        <option value="past">Past Due</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="is_revisit_today">
                                        Revisit Today
                                        <i class="fa fa-info-circle" aria-hidden="true" rel="tooltip" title="Toggle visibility of orders scheduled for revisit today. All = Disable Filter. Yes = Show only the ones that are scheduled to be revisited today. No = Show only the ones that are not scheduled to be revisited today."></i>
                                    </label>
                                    <select class='form-control filter-change' id="is_revisit_today" name="is_revisit_today">
                                        <option value="">All</option>
                                        <option value="1">Yes</option>
                                        <option value="0">No</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Address</th>
                                    <th>Appraisal Type</th>
                                    <th>Loan Reason</th>
                                    <th>Status</th>
                                    <th>Team</th>
                                    <th>Construction</th>
                                    <th>Contract</th>
                                    <th>Worked</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="//cdn.datatables.net/plug-ins/1.10.16/pagination/full_numbers_no_ellipses.js"></script>
    <script type="text/javascript" src="{{ masset('js/appraisal/purchase_pipeline/index.js') }}"></script>
    <style>
        .options_menu {
            left: -110px!important;
        }
        div.dataTables_wrapper div.dataTables_processing {
            z-index: 9999;
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200px;
            margin-left: -100px;
            margin-top: -26px;
            text-align: center;
            padding: 0;
            font-size: 16px;
            color: #404040;
        }
    </style>
@endpush

