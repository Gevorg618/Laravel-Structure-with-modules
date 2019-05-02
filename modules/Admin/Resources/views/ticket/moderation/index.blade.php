@extends('admin::layouts.master')
@section('title', 'Ticket Multi-Moderation')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Ticket', 'url' => '#'],
        ['title' => 'Multi-Moderation', 'url' => route('admin.ticket.moderation')]
    ]
])
@endcomponent

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">
                        <div class="pull-right">
                            <a href="{{ route('admin.ticket.moderation.create') }}"
                               class="btn btn-sm btn-primary">Add Multi-Mod</a>
                        </div>
                    </div>

                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Description</th>
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
      $(function () {
        $app.datatables('#datatable', '{!! route('admin.ticket.moderation.data') !!}', {
          columns: [
            {data: 'title'},
            {data: 'description'},
            {data: 'is_active'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
          ],
          order: [[0, 'asc']]
        });
      });
    </script>
@endpush