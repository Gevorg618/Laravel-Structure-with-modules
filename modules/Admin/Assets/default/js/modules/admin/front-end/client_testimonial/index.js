/* eslint-disable */
$(function () {
    $app.datatables('#datatable', '/admin/frontend-site/client-testimonials/data', {
        columns: [
            {data: 'name', orderable: false},
            {data: 'title', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ],
        "pageLength": 10,
    });
    setTimeout(() => {
        $(document).on('submit', '.delete-form', function (e) {
            $(this).find(':input[type=submit]').attr('disabled', true);
        })
    }, 1000);
});