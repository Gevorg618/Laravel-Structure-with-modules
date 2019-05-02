$(function() {
    $app.datatables('#under', 'review-pipeline/under-review-data', {
        columns: [
            { data: 'order', orderable: false, searchable: false },
            { data: 'client', orderable: false, searchable: false },
            { data: 'address', orderable: false, searchable: false },
            { data: 'status', orderable: false, searchable: false },
            { data: 'last_log', orderable: false, searchable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).find('td:eq(0)').html($('<div />').html(data.order).text());
            $(row).addClass('appr_order_show').attr('id', `appr_order_${data.id}`);
        },
        "searching": false
    });

    $app.datatables('#waiting', 'review-pipeline/waiting-for-approval', {
        columns: [
            { data: 'order', orderable: false, searchable: false },
            { data: 'client', orderable: false, searchable: false },
            { data: 'address', orderable: false, searchable: false },
            { data: 'status', orderable: false, searchable: false },
            { data: 'last_log', orderable: false, searchable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).find('td:eq(0)').html($('<div />').html(data.order).text());
            $(row).addClass('appr_order_show').attr('id', `appr_order_${data.id}`);
        },
        "searching": false
    });
});