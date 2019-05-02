$(function () {
    $app.datatables('#datatable', '/admin/frontend-site/navigation-menu/data', {
        columns: [
            {data: 'title', orderable: false},
            {data: 'url', orderable: false},
            {data: 'slug', orderable: false},
            {
                data: 'is_drop_down',
                orderable: false,
                render: function (data) {
                    return data ? 'Yes' : 'No'
                }
            },
            {
                data: 'is_active',
                orderable: true,
                render: function (data) {
                    return data ? 'Yes' : 'No'
                }
            },
            {
                data: 'is_quick_link',
                orderable: true,
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