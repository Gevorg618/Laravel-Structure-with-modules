$(function () {
    $app.datatables('#datatable', '/admin/frontend-site/contact-us/data', {
        columns: [
            {data: 'lat', orderable: false},
            {data: 'lng', orderable: false},
            {data: 'receivers', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(2)').html($('<div />').html(data.receivers).text());
        },
        "pageLength": 10,
    });
});