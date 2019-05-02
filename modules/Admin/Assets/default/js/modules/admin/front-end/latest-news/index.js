$(function () {
    $app.datatables('#datatable', '/admin/frontend-site/latest-news/data', {
        columns: [
            {data: 'title', orderable: false},
            {data: 'short_description', orderable: false},
            {
                data: 'is_active',
                orderable: true,
                searchable: false,
                render: function (data) {
                    return data ? 'Yes' : 'No'
                }
            },
            {data: 'created_at', orderable: true, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "pageLength": 10,
        "order": [[ 3, 'desc' ]]
    });
    setTimeout(() => {
        $(document).on('submit', '.delete-form', function (e) {
            $(this).find(':input[type=submit]').attr('disabled', true);
        })
    }, 1000);
});