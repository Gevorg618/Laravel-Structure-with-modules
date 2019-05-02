@extends('admin::layouts.master')

@section('title', 'Custom Email Templates')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Management', 'url' => '#'],
      ['title' => 'Custom Email Templates', 'url' => route('admin.management.custom-email-templates')]
    ],
    'actions' => [
      ['title' => 'Add Custom Email Template', 'url' => route('admin.management.custom-email-templates.create')],
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
                                    <th>Created Date</th>
                                    <th>Updated Date</th>
                                    <th>Key</th>
                                    <th>Software Fee</th>
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
        $app.datatables('#datatable', '{!! route('admin.management.custom-email-templates.data') !!}', {
            columns: [
                { data: 'title' },
                { data: 'created_date' },
                { data: 'last_updated_date' },
                { data: 'email_key' },
                { data: 'software_fee' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush