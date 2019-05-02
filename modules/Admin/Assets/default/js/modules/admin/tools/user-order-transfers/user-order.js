function checkEmailRegex(param) {
    var emailReg = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return emailReg.test(param);
}

function notificationErrorMessage(message) {
    toastr['error'](message, 'error'.toUpperCase());
};

$(document).on("click", ".load-info", function() { 

        $('#user_info_content').empty();

        var from = $("#from_user").val();
        var to = $("#to_user").val();
        var validate = true;

        if (from == '' || to == '') {
            validate = false;
            notificationErrorMessage('Please fill required fields');
        }

        var fromType = checkEmailRegex(from);

        if (fromType) { 
            fromType = 'email'
        }

        var toType = checkEmailRegex(to);

        if (toType) { 
            toType = 'email'
        }

        if (validate) {
            $.ajax({
                type: 'GET',
                url: '/admin/tools/user-order-transfers/load-info',
                dataType: 'json',
                data: {
                    from_user: from,
                    to_user: to,
                    from_type:fromType,
                    to_type:toType
                },
                beforeSend: function () {
                    $('.load-info').prop('disabled', true);
                },
                success: function(data) {

                    $('.load-info').prop('disabled', false);

                    if (data.success) {
                        $('#user_info_content').html(data.html);
                    } else {
                       notificationErrorMessage(data.message); 
                    }
                }
            }); 
        }
        

});

$(document).on("click", ".load-order-transfered", function() { 
    var transferedOrderId  = $(this).attr('data-transfer-id');

    $.get( '/admin/tools/user-order-transfers/transfered-orders/'+transferedOrderId, function( data ) {
        $("#content_orders").html(data);
        $("#modal-orders").modal('show');
    });
    
})

$(document).on("click", ".transfer-user", function() { 

        $('#user_info_content').empty();

        var from = $("#from_user").val();
        var to = $("#to_user").val();
        var validate = true;

        if (from == '' || to == '') {
            validate = false;
            notificationErrorMessage('Please fill required fields');
        }

        var fromType = checkEmailRegex(from);

        if (fromType) { 
            fromType = 'email'
        }

        var toType = checkEmailRegex(to);

        if (toType) { 
            toType = 'email'
        }

        if (validate) {
            $.ajax({
                type: 'GET',
                url: '/admin/tools/user-order-transfers/load-info',
                dataType: 'json',
                data: {
                    from_user: from,
                    to_user: to,
                    from_type:fromType,
                    to_type:toType
                },
                beforeSend: function () {
                    $('.load-info').prop('disabled', true);
                },
                success: function(data) {

                    $('.load-info').prop('disabled', false);

                    if (data.success) {
                        $('#user_info_content').html(data.html);
                    } else {
                       notificationErrorMessage(data.message); 
                    }
                }
            }); 
        }
        

});