@extends('admin::layouts.master')

@section('title', 'Sales Taxes')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'Sales Tax', 'url' => route('admin.management.sale.tax.index')]
],
'actions' => [
  ['title' => 'Add Sales Tax', 'url' => route('admin.management.sale.tax.create')],
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
                                    <th>Name</th>
                                    <th>Counties</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="user-types"></div>
    <div id="viewed-users"></div>
@stop

@push('scripts')
<script>
    $(function() {
        $app.datatables('#datatable', '{!! route('admin.management.sale.tax.data') !!}', {
            columns: [
                { data: 'state' },
                { data: 'name' },
                { data: 'counties' },
            ],
            order : [ [ 0, 'asc' ] ]
        });
    });
    $(document).ready(function () {
        $(document).on('click','.user-types',function(e){
            e.preventDefault();
            var _token = '{{ csrf_token() }}';
            var ids = $(this).data('types');
            $.ajax({
                type:'POST',
                url:'{{ route('admin.management.announcements.user-types') }}',
                data:{_token:_token,ids:ids},
                success:function(data){
                    $('#user-types').html(data);
                    $('#userTypesModal').modal();
                }
            });
        });
        $(document).on('click','.viewed',function(e){
            e.preventDefault();
            var _token = '{{ csrf_token() }}';
            var id = $(this).data('id');
            $.ajax({
                type:'POST',
                url:'{{ route('admin.management.announcements.viewed') }}',
                data:{_token:_token,id:id},
                success:function(data){
                    $('#viewed-users').html(data);
                    $('#viewedModal').modal();
                }
            });
        });
    })
</script>
@endpush