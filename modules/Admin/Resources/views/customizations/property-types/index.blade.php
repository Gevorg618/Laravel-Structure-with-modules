@extends('admin::layouts.master')

@section('title', 'Appraisal Property Types')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Customizations', 'url' => '#'],
      ['title' => 'Property Types', 'url' => route('admin.appraisal.property-types.index')]
    ],
    'actions' => [
      ['title' => 'Add Appraisal Property Type', 'url' => route('admin.appraisal.property-types.create')],
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
                                    <th>Description</th>
                                    <th>Mismo</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.property-types.data') !!}', {
            columns: [
                { data: 'id' },
                { data: 'descrip' },
                { data: 'mismo_label' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush