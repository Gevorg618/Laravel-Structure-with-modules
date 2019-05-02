$(function () {
    $app.datatables('#datatable', '/admin/frontend-site/services/data', {
        columns: [
            {data: 'icon', orderable: false},
            {data: 'title', orderable: false},
            {data: 'description', orderable: false},
            {data: 'actions', orderable: false}
        ],
        "pageLength": 10,
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').html($('<div />').html(data.icon).text());
            $( row ).find('td:eq(3)').html($('<div />').html(data.actions).text());
        },
    });
    setTimeout(() => {
        $(document).on('submit', '.delete-form', function (e) {
            $(this).find(':input[type=submit]').attr('disabled', true);
        })
    }, 1000);
});