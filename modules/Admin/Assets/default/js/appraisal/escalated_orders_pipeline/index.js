$(function() {
    $pendingTable = $('#datatable').dataTable({
        "serverSide": true,
        stateSave: true,
        ajax: {
            type: "POST",
            url: 'escalated-orders/filter-data',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(d) {
                $('tr').css('opacity', 0.8);
                var filters = {
                    'is_rush': $('#is_rush').val(),
                    'team': $('#team').val(),
                    'status': $('#status').val(),
                    'client': $('#client').val(),
                    'due_date': $('#date').val(),
                    'quick_filter': $('#filter').val(),
                    'escalated-pipeline': 1
                };
                d.filters = filters;
            }
        },
        oLanguage: {
            sProcessing: 'Please wait - loading...'
        },
        processing: true,
        'pagingType': 'simple_numbers_no_ellipses',
        columns: [
            { "data": "id" },
            { "data": "address" },
            { "data": "appraisal" },
            { "data": "loanpurposetitle" },
            { "data": "statustitle" },
            { "data": "worked", searchable: false },
            { "data": "content", searchable: false, sortable: false },
            { "data": "action", searchable: false, sortable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).find('td:eq(0)').html($('<div />').html(data.id).text());
            $(row).find('td:eq(5)').html($('<div />').html(data.worked).text());
            $(row).find('td:eq(6)').html($('<div />').html(data.content).text());
            $(row).addClass(data.is_rush ? "row-is-rush success" : '').addClass(data.due_date < Math.round(new Date() / 1000) ? 'due-date-past danger' : '');
        },
        "order": [
            [1, "desc"]
        ],
        "drawCallback": function() {
            $('tr').css('opacity', 1);
            $('.mark-as-worked').on('click', function() {
                var $id = $(this).attr('data-id');
                if (!$id) {
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: 'escalated-orders/update-data',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $id
                    }
                });
                $pendingTable.api().ajax.reload();
            });
        }
    });

    $('.filter-change').on('change', function() {
        $pendingTable.api().ajax.reload();
    });

    $('input[name="date"]').daterangepicker({
        "singleDatePicker": true,
        autoUpdateInput: false,
        locale: {
            format: 'MM/DD/YYYY'
        }
    }, function(start, end, label) {});

    $('input[name="date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
        $pendingTable.api().ajax.reload();
    });
})