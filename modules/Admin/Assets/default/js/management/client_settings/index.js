url_client = $('input[name=client_data_url]').val()
$('.bootstrap-multiselect').multiselect();
    table = $('#datatable').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        "type":'post',
        "url": url_client,
        "data": function ( d ) {
            $.extend(d, {'sales': $('#sales_rep').val(), 'state': $('#state').val()});
        }
    },
    "columns": [
        {"data": "descrip"},
        {"data": "address1"},
        {"data": "city"},
        {"data": "state"},
        {"data": "salesperson", searchable: false},
        { data: 'action', name: 'action', orderable: false, searchable: false}
    ]
});

// Register event
$('.filter-change').change(function() {
    table.ajax.reload();
})
