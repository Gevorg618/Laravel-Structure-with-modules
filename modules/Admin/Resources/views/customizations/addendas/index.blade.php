@extends('admin::layouts.master')

@section('title', 'Appraisal Addendas')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'Addendas', 'url' => route('admin.appraisal.addendas')]
],
'actions' => [
   ['title' => 'Add Appraisal Addendas', 'url' => route('admin.appraisal.addendas.create')],
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
                                    <th>Description</th>
                                    <th>Invest</th>
                                    <th>Price</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.addendas.data') !!}', {
            columns: [
                { data: 'descrip' },
                { data: 'invest' },
                { data: 'price' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush