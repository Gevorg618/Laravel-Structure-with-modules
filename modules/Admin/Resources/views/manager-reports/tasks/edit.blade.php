@extends('admin::layouts.master')

@section('title', 'DocuVault Report Generator')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Tasks', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row first_content" >
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">                        
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    {!! Form::open(['route' => 'admin.reports.generator.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12"> Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange',
                                             isset($data['datefrom']) && isset($data['dateto']) ? date('d/m/Y', strtotime($data['datefrom'])) .' - '. date('d/m/Y', strtotime($data['dateto'])):
                                             '01/01/2008 - '. date('d/m/Y', strtotime(time())),
                                            ['class' => 'form-control daterange']) 
                                            !!}
                                        </div>                                        
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Date type
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('datetype', $dateTypes, isset($data['datetype']) ? $data['datetype'] : null, ['class' => 'form-control']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> User Groups
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('client[]', $clients, isset($data['client']) ? $data['client']: null, ['class' => 'form-control multiselect client', 'id' => 'client' ,'multiple' => 'multiple' , 'data-live-search' => "true",
                                            ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="lenders" class="control-label col-lg-3 col-xs-12"> Wholesale Lenders
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('lenders[]', $lenders, isset($data['lender']) ? $data['lender'] : null, ['class' => 'form-control multiselect lenders','multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="columns" class="control-label col-lg-3 col-xs-12"> Columns
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('columns[]', $columns, isset($data['columns']) ? $data['columns'] : null, ['class' => 'form-control multiselect ','multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-6">
                                        <label name="status" class="control-label col-lg-3 col-xs-12"> Status
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('status', $statuses, isset($data['status']) ? $data['status'] : null, ['class' => 'form-control', 'id' => 'status']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="team" class="control-label col-lg-3 col-xs-12"> Team
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('team', $teams, isset($data['team']) ? $data['team'] : null, ['class' => 'form-control', 'id' => 'team']) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Client Approval 
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('is_client_approval', ['Y' => 'Yes', 'N' => 'No', '' => '-- ALL --'], isset($data['is_client_approval']) ? $data['is_client_approval']: null, ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Appraiser
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            <input type="hidden" name="appr_id" id="appr_id">
                                            {{ Form::text('appraiser_name',  isset($data['appraiser_name']) ? $data['appraiser_name'] : null, ['class' => 'form-control typeahead']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="apprTypes" class="control-label col-lg-3 col-xs-12"> Appraisal Types
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('type[]', $apprTypes, isset($data['type']) ? $data['type'] : null, ['class' => 'form-control multiselect ', 'id' => 'appr_types', 'multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label name="apprTypes" class="control-label col-lg-3 col-xs-12"> States
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('states[]', $states, isset($data['state']) ? $data['state'] : null, ['class' => 'form-control multiselect ', 'id' => 'states', 'multiple' => 'multiple' , 'data-live-search' => "true",
                                             ]) }}
                                        </div>
                                    </div>
                                    
                                    <div class="form-group col-md-12">
                                        <div class="col-lg-6 col-xs-12">
                                            <button type="submit" class="btn btn-primary generate-report download-report">Download</button>
                                            <button type="button" class="btn btn-warning" id="update_as_task">Update Task</button>
                                        </div>
                                    </div>    
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row hidden second_content" >
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body">                        
                        <div class="form-body">
                            <div class="row">
                                <div class="col-lg-12 second_content_data">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="task_id" value="{{ $task->id }}">
@stop
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
    <script type="text/javascript">

            var FormValidation = function () {

            // basic validation
            var customPagesManagerRequestValidation = function() {

                // for more info visit the official plugin documentation:
                // http://docs.jquery.com/Plugins/Validation

                var form1 = $('#form-rcreate-task');
                var error1 = $('.alert-danger', form1);
                var success1 = $('.alert-success', form1);
                
                function notificationErrorMessage() {
                    toastr['error']('Please fill required fields', 'error'.toUpperCase());
                };

                form1.validate({
                    errorElement: 'span', //default input error message container
                    errorClass: 'help-block help-block-error', // default input error message class
                    focusInvalid: false, // do not focus the last invalid input
                    ignore: "",  // validate all fields including form hidden input
                    messages: {
                        title: {
                            required:"The Title is required"
                        },
                        subject: {
                            required:"The Email Subject is required"
                        },
                        emails: {
                            required:"The Email Task is required"
                        },
                        content : {
                            required:"The Email Task is required"
                        }

                    },
                    rules: {
                        title: {
                            required: true
                        },
                        subject: {
                            required: true
                        },
                        emails: {
                            required: true
                        },
                        content: {
                            required: true
                        }
                    },

                    invalidHandler: function (event, validator) { //display error alert on form submit
                        success1.hide();
                        error1.show();
                        notificationErrorMessage();
                    },

                    errorPlacement: function (error, element) { // render error placement for each input type
                    },

                    highlight: function (element) { // hightlight error inputs
                        $(element)
                            .closest('.form-group').addClass('has-error'); // set error class to the control group
                    },

                    unhighlight: function (element) { // revert the change done by hightlight
                        $(element)
                            .closest('.form-group').removeClass('has-error'); // set error class to the control group
                    },

                    success: function (label) {
                        label
                            .closest('.form-group').removeClass('has-error'); // set success class to the control group
                    },
                    submitHandler: function (form) {
                        success1.show();
                        error1.hide();
                        form.submit();
                    }
                });


            }

            return {
                //main function to initiate the module
                init: function () {
                    customPagesManagerRequestValidation();
                }

            };

        }();

        $(document).on("click","#update_as_task",function() {
            
            var states = $('#states option:selected').map(function() { return $(this).text(); }).get();            
            var apprTypes = $('#appr_types option:selected').map(function() { return $(this).text(); }).get();
            var client = $('#client option:selected').map(function() { return $(this).text(); }).get(); 
            var team = $('#team option:selected').text(); 
            var status = $('#status option:selected').text();  

            var formData = {states:states, apprTypes: apprTypes, clients: client, team: team, status: status};
            var taskId = $('#task_id').val();
            var form = $('#form-report-show').serializeArray();
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $.ajax({
                url: '/admin/manager-reports/tasks/render-task/'+taskId,
                method: 'POST',
                data:form,
                success: function(data)
                {
                    $('.second_content_data').html(data);
                    CKEDITOR.replace('content');

                    FormValidation.init();                  
                }
            });


            $('.first_content').slideUp();
            $('.second_content').removeClass('hidden');
        });
        
        function getFormData($form){
            var unindexed_array = $form.serializeArray();
            var indexed_array = {};

            $.map(unindexed_array, function(n, i){
                indexed_array[n['name']] = n['value'];
            });

            return indexed_array;
        }

        $(document).on("click",".cancel_task",function() {
            $('.second_content').addClass('hidden');
            $('.first_content').slideDown();            
        });

        $(document).on('change', '#minutes, #hours, #weekday, #monthday', function() {
            updatepreview();
        });

        function updatepreview()
        {
            var dd_wday  = new Array();
            
            dd_wday[0]   = 'Sunday';
            dd_wday[1]   = 'Monday';
            dd_wday[2]   = 'Tuesday';
            dd_wday[3]   = 'Wednesday';
            dd_wday[4]   = 'Thursday';
            dd_wday[5]   = 'Friday';
            dd_wday[6]   = 'Saturday';
            
            var output       = '';
            
            chosen_min   = $('#minutes').val();
            chosen_hour  = $('#hours').val();
            chosen_wday  = $('#weekday').val();
            chosen_mday  = $('#monthday').val();
            
            var output_min   = '';
            var output_hour  = '';
            var output_day   = '';
            var timeset      = 0;
            
            if ( chosen_mday == -1 && chosen_wday == -1 )
            {
                output_day = '';
            }
            
            if ( chosen_mday != -1 )
            {
                output_day = 'On day '+chosen_mday+'.';
            }
            
            if ( chosen_mday == -1 && chosen_wday != -1 )
            {
                output_day = 'On ' + dd_wday[ chosen_wday ]+'.';
            }
            
            if ( chosen_hour != -1 && chosen_min != -1 )
            {
                output_hour = 'At '+chosen_hour+':'+formatnumber(chosen_min)+'.';
            }
            else
            {
                if ( chosen_hour == -1 )
                {
                    if ( chosen_min == 0 )
                    {
                        output_hour = 'On every hour';
                    }
                    else
                    {
                        if ( output_day == '' )
                        {
                            if ( chosen_min == -1 )
                            {
                                output_min = 'Every minute';
                            }
                            else
                            {
                                output_min = 'Every '+chosen_min+' minutes.';
                            }
                        }
                        else
                        {
                            output_min = 'At '+formatnumber(chosen_min)+' minutes past the first available hour';
                        }
                    }
                }
                else
                {
                    if ( output_day != '' )
                    {
                        output_hour = 'At ' + chosen_hour + ':00';
                    }
                    else
                    {
                        output_hour = 'Every ' + chosen_hour + ' Hours';
                    }
                }
            }
            
            output = output_day + ' ' + output_hour + ' ' + output_min;
            
            $('#runat').val( output );
        }
                                    
        function formatnumber(num)
        {
            if ( num == -1 )
            {
                return '00';
            }
            if ( num < 10 )
            {
                return '0'+num;
            }
            else
            {
                return num;
            }
        }      
        
        $('input.typeahead').typeahead({
            source:  function (query, process) {
                return $.get('{!! route('admin.reports.generator.search') !!}', { query: query }, function (data) {
                
                    objects = [];
                    map = {};
                
                    $.each(data, function(i, object) {
                        map[object.label] = object;
                        objects.push(object.label);
                    });

                    process(objects);                
                });
            },
            updater: function(item) {
                $('#appr_id').val(map[item].value);
                return item;
            }
        });

        $(function() {
            $('input[name="daterange"]').daterangepicker();
            $('.multiselect').selectpicker();
        });

    </script>
@endpush