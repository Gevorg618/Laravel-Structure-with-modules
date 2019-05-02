function notificationErrorMessage(message) {
    toastr['error'](message, 'error'.toUpperCase());
};

function notificationSuccessMessage(message) {
    toastr['success'](message, 'success'.toUpperCase());
};

$(document).on("click", "#order_geo_code", function() { 

    validate = true;

    var orderId = $('#orderId').val();

    if (orderId == '') {
        validate = false;
        notificationErrorMessage('Please fill required field');
    }

    if (validate) {

        $.ajax({
            type: 'GET',
            url: '/admin/geo/google-coding/geo-code/'+orderId,
            beforeSend: function () {
                $('.load-info').prop('disabled', true);
            },
            success: function(data) {
                
                $('.load-info').prop('disabled', false);

                if (data.success) {

                    var html = '<p><b>Latitude:</b>' + data.data.lat + '</p>';
                        html += '<p><b>Longitude:</b>' + data.data.long + '</p>';
                    $('#order_address_info').html(html);
                    notificationSuccessMessage('Your Request was successfuly ended');
                    $('#datatable').DataTable().ajax.reload();
                } else {
                   notificationErrorMessage(data.message); 
                }
            }
        }); 
    }
});