@extends('admin::layouts.master')

@section('title', 'Customizations')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraisal Order Statuses', 'url' => '#']
    ],
    'actions' => [
      ['title' => 'Add Status', 'url' => route('admin.appraisal.appr-statuses.create')]
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
                                    <th>Status Select Order</th>
                                    <th>Title</th>
                                    <th>Title</th>
                                    <th>Appraiser Title</th>
                                    <th>Block Appraiser Actions</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.appr-statuses.data') !!}', {
            columns: [
                { data: 'id' },
                { data: 'status_select_order' },
                { data: 'descrip' },
                { data: 'client_title' },
                { data: 'appraiser_title' },
                { data: 'block_appraiser_actions' },
                { data: 'options', name: 'options', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush