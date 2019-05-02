@extends('admin::layouts.master')


@section('title', isset($logo) ? "Edit Logo" : 'Create Logo')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Support tickets', 'url' => '#'],
        ['title' => isset($logo) ? "Edit Logo" : 'Create Logo', 'url' => isset($logo) ? route('admin.tools.logos.edit', ['id' => $logo->id]) : route('admin.tools.logos.create')]
    ]
])

@section('content')
    <div class="row">
        <div class="col-lg-6">
            @if(isset($logo))
                {{ Form::model( $logo, ['route' => ['admin.tools.logos.update', 'id' => $logo->id], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                {{method_field('put')}}
            @else
                {{ Form::open(['route' => ['admin.tools.logos.store'], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{isset($logo) ? 'Updating' : 'Creating'}}</h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title'), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                {{ Form::label('start_date', 'Start Date', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                                <div class="col-lg-12 col-xs-12">
                                                    <input class="form-control" type="text" id="start_date" name="start_date" value="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                {{ Form::label('end_date', 'End Date', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                                <div class="col-lg-12 col-xs-12">
                                                    <input class="form-control" type="text" id="end_date" name="end_date" value="">
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    @if(isset($logo))
                                        <div class="form-group">
                                            {{ Form::label('image', 'Image', ['class' => 'col-lg-3 col-xs-12']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::file('image', old('image')) }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            {{ Form::label('image', 'Image', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::file('image', ['required' => 'required']) }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">Save</button>
                                    <a href="{{route('admin.tools.logos')}}" class="btn btn-default pull-right" style="margin-right: 10px;">Cancel</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop

@push('scripts')
    <script>
        $(function() {
            var dateNow = new Date(),
                startDate, endDate,
                reservedDates = [];

            @if(isset($logo))
                startDate = new Date(moment.unix({{$logo['start_date']}}).format('MM/DD/YYYY'));
                endDate = new Date(moment.unix({{$logo['end_date']}}).format('MM/DD/YYYY'));
            @else
                startDate = dateNow;
                endDate = dateNow;
            @endif;
            @foreach($dates as $date)
                reservedDates.push("{{$date}}")
            @endforeach
            $('input[name="start_date"]').daterangepicker({
                isInvalidDate: function (date) {
                    var formatted = date.format('MM/DD/YYYY');
                    return reservedDates.indexOf(formatted) > -1;
                },
                "singleDatePicker": true,
                "startDate": startDate,
                "minDate" : dateNow,
                locale: {
                    format: 'MM/DD/YYYY'
                },
            }, function(start, end, label) {
                endDate = start;
                endDateFunc();
            });
            var validDate = function(endDate, reservedDates) {
                var dates = [];
                reservedDates.forEach(function(item){
                    if(new Date(endDate) < new Date(item)) {
                        dates.push(item);
                    }
                });
                return dates[0];
            }

            var endDateFunc = function() {
                $('input[name="end_date"]').daterangepicker({
                    "singleDatePicker": true,
                    "minDate" : endDate,
                    'maxDate': validDate(endDate, reservedDates),
                    isInvalidDate: function (date) {
                        var formatted = date.format('MM/DD/YYYY');
                        return reservedDates.indexOf(formatted) > -1;
                    },
                    locale: {
                        format: 'MM/DD/YYYY'
                    }
                }, function(start, end, label) {
                });
            }
            endDateFunc();
        });
    </script>
@endpush
