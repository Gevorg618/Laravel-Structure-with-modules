$(function () {
    $app.datatables('#datatable', '/admin/frontend-site/team-member/data', {
        columns: [
            {data: 'title', orderable: false},
            {data: 'name', orderable: false},
            {
                data: 'social_links',
                orderable: false,
                searchable: false,
                render: function (data) {
                    return data ? Object.keys(data) : 'No';
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