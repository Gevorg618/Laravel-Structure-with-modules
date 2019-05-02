@extends('admin::layouts.master')

@section('title', 'Admin Groups')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Admin Groups', 'url' => route('admin.management.admin-groups')]
    ],
    'actions' => [
        ['title' => 'Add Admin Group', 'url' => route('admin.management.admin-groups.create')],
        ['title' => 'Clear Cache', 'class' => 'btn-danger', 'url' => route('admin.management.admin-groups.clear-cache')],
    ]
])
@endcomponent

@section('content')
    <div class="modal fade" id="confirm_delete" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delete Admin Group</h4>
                </div>
                <div class="modal-body">
                    <p>Are you sure you would like to proceed with this action?</p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{route('admin.management.admin-groups.delete')}}" class="form-horizontal">
                        {{ csrf_field() }}
                        <input type="hidden" name="id" value="" class="delete_id">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
                                        <th>Protected</th>
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
            $app.datatables('#datatable', '{!! route('admin.management.admin-groups.data') !!}', {
                columns: [
                    { data: 'title', orderable: false },
                    { data: 'is_protected', orderable: false },
                    { data: 'action', name: 'action', orderable: false }
                ],
                "searching": false
            });

            $('body').on('click', '.admin_groups_delete_button', function() {
                var id = $(this).data('id');
                $('.delete_id').val(id);
            });
        });

    </script>
@endpush
