var FormValidation = function () {

    // basic validation
    var appraiserGroupRequestValidation = function() {
        // for more info visit the official plugin documentation:
        // http://docs.jquery.com/Plugins/Validation

        var form1 = $('#appraiser-group');
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
                description: {
                    required:"The Description is required"
                },
                manager: {
                    required:"The Manager is required"
                }
            },
            rules: {
                title: {
                    required: true
                },
                description: {
                    required: true
                },
                manager: {
                    required: true
                }
            },

            invalidHandler: function (event, validator) { //display error alert on form submit
                success1.hide();
                error1.show();
                notificationErrorMessage();
            },

            errorPlacement: function (error, element) { // render error placement for each input type
                
                var cont = $(element).parent('.input-group');

                if (cont) {
                    cont.after(error);
                } else {
                    element.after(error);
                }
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
            appraiserGroupRequestValidation();
        }

    };

}();

jQuery(document).ready(function() {
    FormValidation.init();
});