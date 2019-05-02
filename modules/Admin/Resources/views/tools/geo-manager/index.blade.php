@extends('admin::layouts.master')

@section('title', 'GEO States')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'GEO Manager', 'url' => '#'],
      ['title' => 'GEO', 'url' => route('admin.tools.geo.index')]
    ],
    'actions' => [
      ['title' => 'Add GEO', 'url' => route('admin.tools.geo.create')]
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
                                    <th>State</th>
                                    <th>State Region</th>
                                    <th>State Timezone</th>
                                    <th>State Adjacent States</th>
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
        $app.datatables('#datatable', '{!! route('admin.tools.geo.data') !!}', {
            columns: [
                { data: 'state' },
                { data: 'region' },
                { data: 'timezone' },
                { data: 'adjacent_states' },
                { data: 'options', name: 'options', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush