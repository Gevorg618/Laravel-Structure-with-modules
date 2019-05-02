@extends('admin::layouts.master')

@section('title', 'Ticket categories')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Support tickets', 'url' => '#'],
        ['title' => 'Ticket categories', 'url' => route('admin.ticket.categories.index')]
    ],
    'actions' => [
        ['title' => 'Add Ticket Category', 'url' => route('admin.ticket.categories.create')],
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
        $app.datatables('#datatable', '{!! route('admin.ticket.categories.data') !!}', {
            columns: [
                { data: 'name' },
                { data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
</script>
@endpush