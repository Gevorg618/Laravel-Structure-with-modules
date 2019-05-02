@extends('admin::layouts.master')

@section('title', 'Appraisal Loan Types')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Customizations', 'url' => '#'],
      ['title' => 'Loan Type', 'url' => route('admin.appraisal.loantype')]
    ],
    'actions' => [
      ['title' => 'Add Appraisal Loan Type', 'url' => route('admin.appraisal.loantype.create')],
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
                                    <th>Mismo</th>
                                    <th>Default</th>
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
        $app.datatables('#datatable', '{!! route('admin.appraisal.loantype.data') !!}', {
            columns: [
                { data: 'descrip' },
                { data: 'mismo_label' },
                {
                    data: 'is_default',
                    render: function ( data, type, row ) {
                        return !data ? 'No' : 'Yes';
                    }
                },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush