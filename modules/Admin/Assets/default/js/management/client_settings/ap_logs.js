// Add AP Log
$('#add_ap_log').on('click', function() {
    var orderIds = $('#log_order_ids').val();
    var message = $('#log_message').val();
    var groupId = $("#ap_log_group_id").val();
    var url = $("#ap_log_url").val();


    if(!message) {
        alert('Please enter a message');
        return false;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            'groupId': groupId,
            'orderIds': orderIds,
            'message': message
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
            if(data.error && data.error != '') {
                alert(data.error);
                return;
            }

            $('#ap_logs_div').html(data);

        }
    });
});
