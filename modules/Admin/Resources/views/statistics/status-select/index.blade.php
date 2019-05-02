@extends('admin::layouts.master')

@section('title', 'Statistics & User Tracking')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Statistics & User Tracking', 'url' => '#'],
      ['title' => 'Status Select Statistics', 'url' => route('admin.statistics.index')]
    ]
])
@endcomponent

@section('content')
 
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Quick View</b></h2>
                    <div class="panel-body panel-body-table">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="col-md-2 col-md-offset-1">
                            <p class="text-center">
                                <div class="text-center">
                                    <h1 class="text-danger huge-text">{{ $leftToRevisitCount }}</h1>
                                </div>
                            </p>
                            <h4 class="text-danger text-center">Left To Revisit</h4>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center">
                                <div class="text-center">
                                    <h1 class="text-danger huge-text">{{ $toWorkOnCount }}</h1>
                                </div>
                            </p>
                            <h4 class="text-danger text-center">Total To Work On</h4>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center">
                                <div class="text-center">
                                    <h1 class="text-success load-details" style="cursor: pointer;" data-info="future-revisit">{{ $futureRevisitCount }}</h1>
                                </div>
                            </p>
                            <h4 class="text-success text-center">Future Revisits</h4>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center">
                                <div class="text-center">
                                    <h1 class="text-primary load-details" style="cursor: pointer;" data-info="today-revisit">{{ $todayRevisitCount }}</h1>
                                </div>
                            </p>
                            <h4 class="text-primary text-center">Revisits Today</h4>
                        </div>
                        <div class="col-md-2">
                            <p class="text-center">
                                <div class="text-center">
                                    <h1 class="text-warning load-details " style="cursor: pointer;" data-info="multiple-revisit">{{ $multipleRevisitCount }}</h1>
                                </div>
                            </p>
                            <h4 class="text-warning text-center">Multiple Revisits Today</h4>
                        </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <div class="panel-body panel-body-table">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover" id="orders-datatable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Address</th>
                                        <th>Team</th>
                                        <th>Transfer</th>
                                        <th>Status</th>
                                        <th>Currently Viewing</th>
                                        <th>Revisit Date & Time</th>
                                    </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('admin::statistics.status-select.partials._modal')
@stop

@push('scripts')
    {{-- <script src="{{ masset('js/modules/admin/statistics/dashboard/index.js') }}"></script> --}}
    <script>

    $(document).on("click", ".load-details", function() { 
        
        var loadDetails  = $(this).attr('data-info');

        $.get( '/admin/statistics-user-tracking/status-select/details/'+loadDetails, function( data ) {
                $("#content_details").html(data.details);
                $('.modal-title').html(data.title);
                $("#modal-view").modal('show');
        });
        
    })    
    $(function() {
        
        var requestData = {
            url: '/admin/statistics-user-tracking/status-select/show',
        };

        $app.datatables('#orders-datatable', requestData, {
            columns: [
                 {data: 'id'},
                 {data: 'address'},
                 {data: 'team'},
                 {data: 'transfer'},
                 {data: 'status'},
                 {data: 'viewing'},
                 {data: 'date_time'}
            ],
            iDisplayLength: 100,
            order : false,
            retrieve: false,
            destroy: true,
            searchable: false,
            searching: false,
            orderable: false
        });
    });
      
    </script>
@endpush