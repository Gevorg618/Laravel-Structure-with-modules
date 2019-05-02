@extends('admin::layouts.master')

@section('title', 'Clients')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tiger', 'url' => '#'],
  ['title' => 'Clients', 'url' => route('admin.management.surveys.index')]
],
'actions' => [
  ['title' => 'Add Client', 'url' => route('admin.tiger.clients.create')]
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
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Domain</th>
                                    <th>Charge Fee</th>
                                    <th>Fee</th>
                                    <th>Charge Location</th>
                                    <th>Card On File</th>
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
        $app.datatables('#datatable', '{!! route('admin.tiger.clients.data') !!}', {
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'domain' },
                { data: 'appr_charge_fee' },
                { data: 'appr_fee_amount' },
                { data: 'appr_charge_location' },
                { data: 'card_on_file' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush