@extends('admin::layouts.master')

@section('title', 'Alternative Valuation Order Statuses')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Valuation', 'url' => '#'],
  ['title' => 'Order Statuses', 'url' => route('admin.valuation.orders.status')]
],
'actions' => [
  ['title' => 'Add Order Status', 'url' => route('admin.valuation.orders.status.create')],
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Options</th>
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
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.valuation.orders.status.data') !!}', {
            columns: [
                { data: 'name' },
                { data: 'code' },
//                { data: 'is_protected', searchable: false },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush