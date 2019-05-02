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


$(document).on("click",".download-report",function() {
    
    

    var columnsElement = $('#columns')
    var columns = columnsElement.val();
    columnsElement.closest('.form-group').removeClass('has-error');
    if (columns) {
        $("#loading").show();
        $('#form-report-show').submit();
        setTimeout(function(){ 
            $("#loading").hide();
        }, 7000);
    }  else {
        columnsElement.closest('.form-group').addClass('has-error');
        $("#loading").hide();
    }

});


$(document).on("click","#save_as_task",function() {
    
    $("#loading").show();

    // var states = $('#states option:selected').map(function() { return $(this).text(); }).get();            
    // var apprTypes = $('#appr_types option:selected').map(function() { return $(this).text(); }).get();
    // var client = $('#client option:selected').map(function() { return $(this).text(); }).get(); 
    // var team = $('#team option:selected').text(); 
    // var status = $('#status option:selected').text();
      
       var form = $('#form-report-show').serializeArray();

        // var details = {'states':states, 'apprTypes':apprTypes, 'team': team, 'client': client, 'status': status };

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $.ajax({
            url: '/admin/manager-reports/generator/render-task',
            method: 'POST',
            data:form,
            success: function(data)
            {
                $('.second_content_data').html(data);
                CKEDITOR.replace('content');
                FormValidation.init();
                $("#loading").hide();                  
            }
        });


        $('.first_content').slideUp();
        $('.second_content').removeClass('hidden'); 
    
});

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