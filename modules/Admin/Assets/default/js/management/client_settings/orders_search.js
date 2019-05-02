// Search orders
$('#search_orders').on('click', function () {
    var dateFrom = $('#orders_date_from').val();
    var dateTo = $('#orders_date_to').val();
    var types = $('#orders_types').val();
    var groupId = $('#group_id').val();
    var url = $('#search_orders_url').val();

    if (!dateFrom || !dateTo) {
        alert('Please provide both date from and date to.');
        return false;
    }

    $.ajax({
        url: url,
        type: 'POST',
        data: {
            'groupId': groupId,
            'dateFrom': dateFrom,
            'dateTo': dateTo,
            'types': types
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            $('#search_orders_content').html(data);


        }
    });
});
