@extends('admin::layouts.master')

@section('title', 'Sale Stages')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'CRM', 'url' => '#'],
      ['title' => 'Sale Stages', 'url' => route('admin.crm.sale.stages.index')]
    ],
    'actions' => [
      ['title' => 'Add Sale Stage', 'url' => route('admin.crm.sale.stages.create')],
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
                                    <th>Title</th>
                                    <th>Visible</th>
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
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.crm.sale.stages.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'visible' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush