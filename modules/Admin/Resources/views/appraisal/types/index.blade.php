@extends('admin::layouts.master')

@section('title', 'Customizations')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Appraisal Order Types', 'url' => '#']
    ],
    'actions' => [
      ['title' => 'Add Types', 'url' => route('admin.appraisal.appr-types.create')]
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
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Short Desc</th>
                                    <th>Form</th>
                                    <th>EAD Form</th>
                                    <th>Mismo</th>
                                    <th>FHA</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.appr-types.data') !!}', {
            columns: [
                { data: 'code' },
                { data: 'descrip' },
                { data: 'short_descrip' },
                { data: 'form' },
                { data: 'ead_form' },
                { data: 'mismo_label' },
                { data: 'fha' },
                { data: 'active' },
                { data: 'options', name: 'options', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush