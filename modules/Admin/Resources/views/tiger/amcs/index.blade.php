@extends('admin::layouts.master')

@section('title', 'Tiger AMC')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tiger', 'url' => '#'],
  ['title' => 'AMCs', 'url' => route('admin.tiger.amcs.index')]
],
'actions' => [
  ['title' => 'Add Amc', 'url' => route('admin.tiger.amcs.create')]
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
                                    <th>Active</th>
                                    <th>Company Name</th>
                                    <th>Company Address</th>
                                    <th>Company City</th>
                                    <th>Company State</th>
                                    <th>Company Zip</th>
                                    <th>Company Phone</th>
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
        $app.datatables('#datatable', '{!! route('admin.tiger.amcs.data') !!}', {
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'is_active' },
                { data: 'company_name' },
                { data: 'company_address' },
                { data: 'company_city' },
                { data: 'company_state' },
                { data: 'company_zip' },
                { data: 'company_phone' },
                { data: 'card_on_file' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush