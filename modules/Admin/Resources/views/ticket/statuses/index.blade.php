@extends('admin::layouts.master')

@section('title', 'Ticket Statuses')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tickets', 'url' => '#'],
  ['title' => 'Ticket Statuses', 'url' => route('admin.ticket.statuses.index')]
],
'actions' => [
  ['title' => 'Add Ticket Status', 'url' => route('admin.ticket.statuses.create')],
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
                                    <th>Name</th>
                                    <th>Background Color</th>
                                    <th>Text Color</th>
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
            $app.datatables('#datatable', '{!! route('admin.ticket.statuses.data') !!}', {
                columns: [
                    { data: 'name' },
                    { data: 'bgcolor' },
                    { data: 'textcolor' },
                    { data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                order : [ [ 0, 'asc' ] ]
            });
        });
    </script>
@endpush