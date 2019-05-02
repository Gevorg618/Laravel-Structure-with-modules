@extends('admin::layouts.master')

@section('title', 'EAD Business Units Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraisal', 'url' => '#'],
      ['title' => 'EAD  Business Units Manager', 'url' => route('admin.appraisal.ead-unit')]
    ],
    'actions' => [
      ['title' => 'Add EAD Business Unit', 'url' => route('admin.appraisal.ead-unit.create')],
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
                                    <th>Unit ID</th>
                                    <th>FHA LenderID</th>
                                    <th>Active</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.ead-unit.data') !!}', {
            columns: [
                { data: 'id' },
                { data: 'title' },
                { data: 'unit_id' },
                { data: 'fha_lenderid' },
                { data: 'is_active' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush