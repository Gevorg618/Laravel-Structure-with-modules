@extends('admin::layouts.master')

@section('title', 'Shipping Labels')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'Shipping Labels', 'url' => route('admin.tools.shipping-labels')]
]
])
@endcomponent
@push('heads')
<link href="{{ masset('css/plugins/dataTables/dataTables.colVis.css') }}" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="{{ masset('css/plugins/dataTables/dataTables.checkboxes.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#appraisal">Appraisal Labels ({{ $count['appraisal'] }})</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#docuvault">DocuVault Labels ({{ $count['docuvault'] }})</a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="appraisal" class="tab-pane fade in active">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="panel-body panel-body-table">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="datatable"
                                           data-tab="appraisal">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Order ID</th>
                                            <th>Service</th>
                                            <th>Address</th>
                                            <th>Created Date</th>
                                            <th>Created By</th>
                                            <th>Tracking #</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="row">
                                    <form action="{{ route('admin.tools.shipping-labels.downloadPDF') }}" method="POST"
                                          id="appraisalForm">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <button type="button" data-tab="appraisal" class="printPDF btn btn-success">
                                            Print
                                            Selected Labels
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div id="docuvault" class="tab-pane fade">
                    <div class="ibox float-e-margins">
                        <div class="ibox-content">
                            <div class="panel-body panel-body-table">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover"
                                           id="licenses-datatable"
                                           data-tab="docuVault">
                                        <thead>
                                        <tr>
                                            <th></th>
                                            <th>Order ID</th>
                                            <th>Service</th>
                                            <th>Address</th>
                                            <th>Created Date</th>
                                            <th>Created By</th>
                                            <th>Tracking #</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                                <div class="row">
                                    <form action="{{ route('admin.tools.shipping-labels.downloadPDF') }}" method="POST"
                                          id="docuVaultForm">
                                        {{ csrf_field() }}
                                        {{ method_field('POST') }}
                                        <button type="button" data-tab="docuVault" class="printPDF btn btn-success">
                                            Print
                                            Selected Labels
                                        </button>
                                    </form>
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
<script type="text/javascript" src="{{ masset('js/plugins/dataTables/dataTables.checkboxes.min.js') }}"></script>
<script type="text/javascript">
    $(function () {
        var appraisalData = {
            url: '{!! route('admin.tools.shipping-labels.data') !!}',
            data: function (d) {
                d.orderType = 'appraisal';
            }
        };
        appraisal = $app.datatables('#datatable', appraisalData, {
            columns: [
                {data: 'checkbox'},
                {data: 'order_id'},
                {data: 'service'},
                {data: 'address'},
                {data: 'created_date'},
                {data: 'created_by'},
                {data: 'tracking_number'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {
                    targets: 0,
                    checkboxes: {
                        selectRow: true
                    }
                }
            ],
            select: {
                style: 'multi'
            },
            order: [[0, 'asc']]
        });
        var docuVaultData = {
            url: '{!! route('admin.tools.shipping-labels.data') !!}',
            data: function (d) {
                d.orderType = 'docuvault';
            }
        };
        docuVault = $app.datatables('#licenses-datatable', docuVaultData, {
            columns: [
                {data: 'checkbox'},
                {data: 'order_id'},
                {data: 'service'},
                {data: 'address'},
                {data: 'created_date'},
                {data: 'created_by'},
                {data: 'tracking_number'},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            columnDefs: [
                {
                    targets: 0,
                    checkboxes: {
                        selectRow: true
                    }
                }
            ],
            select: {
                style: 'multi'
            },
            order: [[1, 'asc']]
        });
    });
    $(document).ready(function () {
        $(document).on('click', '.printPDF', function (e) {
            e.preventDefault();
            var project = $(this).data('tab');
            var form = $(this).closest('form').attr('id');
            if (project == 'appraisal') {
                var rows_selected = appraisal.column(0).checkboxes.selected();
            } else {
                var rows_selected = docuVault.column(0).checkboxes.selected();
            }
            // Iterate over all selected checkboxes
            if (rows_selected.length > 0) {
                $.each(rows_selected, function (index, imgLink) {
                    // Create a hidden element
                    $('#' + form).append(
                            $('<input>')
                                    .attr('type', 'hidden')
                                    .attr('name', 'images[]')
                                    .val(imgLink)
                    );
                });
                $('#' + form).submit();
                $('#' + form + ' input[name="images[]"]').remove();
            }
        });
        $(document).on('change', 'input[type="checkbox"]', function () {
            var project = $(this).closest('table').data('tab');
            if (project == 'appraisal') {
                var rows_selected = appraisal.column(0).checkboxes.selected();
            } else {
                var rows_selected = docuVault.column(0).checkboxes.selected();
            }
            var row = (rows_selected.length > 1) ? 'rows' : 'row';
            $(this).closest('.dataTables_wrapper ').find('.dataTables_info .select-info').remove();
            $(this).closest('.dataTables_wrapper ')
                    .find('.dataTables_info')
                    .append('<span class="select-info"><span class="select-item">' +
                    rows_selected.length + ' ' + row + ' selected' +
                    '</span></span>');
        });
    });
</script>
@endpush
