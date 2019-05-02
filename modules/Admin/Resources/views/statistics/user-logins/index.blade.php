@extends('admin::layouts.master')

@section('title', 'User Logins')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'User Logins', 'url' => route('admin.statistics.user.logins')]
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
                            {!! Form::open(['route' => 'admin.statistics.user-logs.data','class' => 'form-horizontal','id' => 'filterForm']) !!}
                            <div class="form-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label name="date_from" class="control-label col-lg-3 col-xs-12">Date From
                                                <span class="required" aria-required="true"></span>
                                            </label>
                                            <div class="col-lg-6 col-xs-12">
                                                {!! Form::text('date_from','',['class' => 'date_from form-control datepicker']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label name="date_from" class="control-label col-lg-3 col-xs-12">Date To
                                                <span class="required" aria-required="true"></span>
                                            </label>
                                            <div class="col-lg-6 col-xs-12">
                                                {!! Form::text('date_to','',['class' => 'date_to form-control datepicker']) !!}
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label name="title" class="control-label col-lg-3 col-xs-12">Admin
                                                <span class="required" aria-required="true"></span>
                                            </label>
                                            <div class="col-lg-6 col-xs-12">
                                                {!! Form::select('admin',$admins,'',['class' => 'admin form-control']) !!}
                                                <span class="help-block title-error-block"></span>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::button('Reset',['class' => 'btn btn-default','id' => 'resetfilter']) !!}
                                            {!! Form::button('Search',['class' => 'btn btn-primary','id' => 'sendFilter']) !!}
                                        </div> 
                                    </div>
                                </div>
                            </div>      
                            {!! Form::close() !!}                            
                        </div>
                    </div>

                    <div class="panel-body panel-body-table hidden" id="showData">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Date</th>
                                    <th>Login Type</th>
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

    $(document).ready(function () {
        
        $('#sendFilter').click(function (e) {
            e.preventDefault();

            var date_from = $('.date_from').val();
            var date_to = $('.date_to').val();
            var admin = $('.admin').val();

            if(date_from.length != 0 && date_to.length != 0) {


                var data = {
                    url: '{!! route('admin.statistics.user.logins.data') !!}',
                    data: { date_from: date_from, date_to: date_to,  admin: admin }
                };

                var dataTable = $app.datatables('#datatable', data, {
                        columns: [
                            {data: 'userid'},
                            {data: 'dts'},
                            {data: 'login'}
                        ],
                        initComplete: function(){
                          $("div.toolbar")
                             .html('<button type="button" class="btn btn-warning"></button>');
                             }, 
                        iDisplayLength: 100,
                        lengthMenu: [ 10, 25, 50, 75, 100 ],
                        retrieve: false,
                        destroy: true,
                        searchable: false,
                        searching: false,
                    });

                $('#showData').removeClass('hidden');
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
            $('#showData').addClass('hidden');
        });

    });
</script>
@endpush

