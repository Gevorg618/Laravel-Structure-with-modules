@extends('admin::layouts.master')

@section('title', 'QC Report')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Manager Reports', 'url' => '#'],
        ['title' => 'QC Report', 'url' => route('admin.manager-reports.qc-report')]
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
                            <div class="container">
                                <div class="" style="padding-bottom: 20px;">
                                    <form method='POST' action="{{route('admin.manager-reports.qc-report.form')}}">
                                        {{ csrf_field() }}
                                        <div class="form-group row">
                                            <div class="col-md-4">
                                                <label for="datefrom">Date <span class="required"></span></label>
                                                <input type="text" name="datefrom" id="datefrom" readonly class="form-control" value="{{$date}}">
                                                <div class="clear"></div>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-md-12" style="text-align:center">
                                                <input type='reset' class='btn btn-success' id='reset_filters' name='reset' value='Reset' />
                                                <input type='submit' class='btn btn-success' id='submit' name='submit' value='Submit' />
                                                <input type='submit' class='btn btn-success' id='download' name='download' value='Download' />
                                            </div>
                                        </div>
                                        <div class="clear"></div>
                                    </form>
                                </div>
                                @if($rows)
                                    <table class="table table-striped table-bordered table-hover" id="datatable">
                                        <thead>
                                            <tr>
                                                <th>Time Range</th>
                                                <th>Reports Uploaded</th>
                                                <th>1st Approved</th>
                                                <th>Sent Back</th>
                                                <th>2nd Approved</th>
                                                <th>Save/Hold</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($rows as $time => $row)
                                                <tr>
                                                    <td>{{$time}}</td>
                                                    <td>{{$row['reports']}}</td>
                                                    <td>{{$row['first_approved']}}</td>
                                                    <td>{{$row['sent_back']}}</td>
                                                    <td>{{$row['second_approved']}}</td>
                                                    <td>{{$row['is_saved']}}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
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
        $('input[name="datefrom"]').daterangepicker({
            "singleDatePicker": true,
            autoUpdateInput: false,
            showDropdowns: true,
            locale: {
                format: 'MM/DD/YYYY'
            }
        }, function(start, end, label) {});

        $('input[name="datefrom"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('MM/DD/YYYY'));
        });
    });
</script>
@endpush
