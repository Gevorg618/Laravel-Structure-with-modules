@extends('admin::layouts.master')

@section('title', 'Exclusionary Profiles')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Lenders', 'url' => '#'],
  ['title' => 'Exclusionary Profiles', 'url' => route('admin.lenders.exclusionary')]
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#profiles">Profiles ({{ $count['profiles'] }})</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#licenses">Licenses ({{ $count['licenses'] }})</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="profiles" class="tab-pane fade in active">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="panel-body panel-body-table">
                                <div class="table">
                                    <div class="row">
                                        <div class="col-md-6">
                                            {{ Form::select('filter',$lenders,null, ['id' => 'filter','class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body panel-body-table">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="datatable">
                                        <thead>
                                        <tr>
                                            <th>Lender</th>
                                            <th>Appraiser ID</th>
                                            <th>Appraiser</th>
                                            <th>Added Date</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="licenses" class="tab-pane fade">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="panel-body panel-body-table">
                                <div class="table">
                                    <div class="row">
                                        <div class="col-md-4">
                                            {{ Form::select('states',$states,null, ['id' => 'state','class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                        <div class="col-md-4">
                                            {{ Form::select('filter',$lenders,null, ['id' => 'licenses-filters','class' => 'form-control multiselect','multiple' => 'multiple']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="panel-body panel-body-table">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                           id="licenses-datatable">
                                        <thead>
                                        <tr>
                                            <th>Lender</th>
                                            <th>Appraiser</th>
                                            <th>License State</th>
                                            <th>License Number</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    $(function () {
        var profilesData = {
            url: '{!! route('admin.lenders.exclusionary.data') !!}',
            data: function (d) {
                d.filter = $('#filter').val();
            }
        };
        profilesTable = $app.datatables('#datatable', profilesData, {
            columns: [
                {data: 'lender'},
                {data: 'appraiser_id'},
                {data: 'appraiser'},
                {data: 'created_date'}
            ],
            order: [[0, 'asc']]
        });
        var licensesData = {
            url: '{!! route('admin.lenders.licenses.data') !!}',
            data: function (d) {
                d.state = $('#state').val();
                d.filter = $('#licenses-filters').val();
            }
        };
         licensesTable = $app.datatables('#licenses-datatable', licensesData, {
            columns: [
                {data: 'lender'},
                {data: 'appraiser'},
                {data: 'license_state'},
                {data: 'license_number'}
            ],
            order: [[0, 'asc']]
        });

    });
    $(document).ready(function () {
        $('#filter').on('change', function () {
            profilesTable.draw();
        });
        $('#state,#licenses-filters').on('change', function () {
            licensesTable.draw();
        });
        $('.multiselect').selectpicker();
    });
</script>
@endpush
