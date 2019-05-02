$(document).ready(function () {
    $app.datatables('#datatable', '/admin/frontend-site/header-carousel/data', {
        columns: [
            {data: 'title', orderable: false},
            {data: 'description', orderable: false},
            {data: 'position', orderable: false},
            {
                data: 'is_active',
                orderable: true,
                searchable: false,
                render: function (data) {
                    return data ? 'Yes' : 'No'
                }
            },
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "pageLength": 10,
    });
    setTimeout(() => {
        $(document).on('submit', '.delete-form', function (e) {
            $(this).find(':input[type=submit]').attr('disabled', true);
        })
    }, 1000);
});