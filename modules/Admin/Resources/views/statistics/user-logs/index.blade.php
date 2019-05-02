@extends('admin::layouts.master')

@section('title', 'User Logs')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Statistics', 'url' => '#'],
  ['title' => 'User Logs', 'url' => route('admin.statistics.user-logs')]
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table">
                            <div class="col-md-9">
                                {!! Form::open(['route' => 'admin.statistics.user-logs.data','class' => 'form-horizontal','id' => 'filterForm']) !!}
                                <div class="col-md-6">
                                    {!! Form::text('date_from','',['class' => 'date_from form-control datepicker','placeholder' => 'Date From']) !!}
                                    {!! Form::text('date_to','',['class' => 'date_to form-control datepicker','placeholder' => 'Date To']) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::select('log_type',$logType,'',['class' => 'log_type form-control','placeholder' => 'Log Type']) !!}
                                    {!! Form::select('admin',$admins,'',['class' => 'admin form-control','placeholder' => 'Admin']) !!}
                                </div>
                                <div class="col-md-6 col-md-offset-6">
                                    <div class="col-md-2 pull-right">
                                        {!! Form::submit('Reset',['class' => 'btn btn-primary','id' => 'resetFilter']) !!}
                                    </div>
                                    <div class="col-md-2 pull-right">
                                        {!! Form::submit('Show',['class' => 'btn btn-success','id' => 'sendFilter']) !!}
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="panel-body panel-body-table" id="showData">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Subject</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal"></div>
@stop

@push('scripts')
<script type="text/javascript">
    $(function () {
        var data = {
            url: '{!! route('admin.statistics.user-logs.data') !!}',
            data: function (d) {
                d.filter = {
                    date_from: $('.date_from').val(),
                    date_to: $('.date_to').val(),
                    log_type: $('.log_type').val(),
                    admin: $('.admin').val()
                }
            }
        };
        userLogs  = $app.datatables('#datatable', data, {
            columns: [
                {data: 'id'},
                {data: 'dts'},
                {data: 'type'},
                {data: 'subject',width:400,name:'info'}
            ],
            order: [[0, 'asc']]
        });
    });
    $(document).ready(function () {
        $('#showData').hide();
        $('#sendFilter').click(function (e) {
            e.preventDefault();
            var date_from = $('.date_from').val();
            var date_to = $('.date_to').val();
            var log_type = $('.log_type').val();
            var admin = $('.admin').val();
            if(date_from.length != 0 && date_to.length != 0 && admin.length != 0){
                userLogs.on('xhr.dt', function() {
                    $('#showData').show();
                });
                userLogs.draw();
            }else{
                alert('Please fill form');
            }
        });
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm'
        });
        $('#resetFilter').click(function (e) {
            e.preventDefault();
            $('#filterForm')[0].reset();
        });
        $(document).on('click','.get-html-content',function(e){
            e.preventDefault();
            var _token = '{{ csrf_token() }}';
            var id = $(this).data('id');
            $.ajax({
                type:'POST',
                url:'{{ route('admin.statistics.user-logs.html-content') }}',
                data:{_token:_token,id:id},
                success:function(data){
                    $('#modal').html(data);
                    $('#myModal').modal();
                }
            });
        });
    });
</script>
@endpush

