$(function() {
    $pendingTable = $('#datatable').dataTable({
        "serverSide": true,
        stateSave: true,
        ajax: {
            type: "POST",
            url: 'purchase-pipeline/data',
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
                    'due_date': $('#due_date').val(),
                    'quick_filter': $('#quick_filter').val(),
                    'escalated': $('#escalated').val(),
                    'pending_review': $('#pending_review').val(),
                    'is_revisit_today': $('#is_revisit_today').val(),
                    'purchase-pipeline': 1
                };
                d.filters = filters;
            }
        },
        columns: [
            { "data": "id" },
            { "data": "client" },
            { "data": "address" },
            { "data": "appraisal" },
            { "data": "loanpurposetitle" },
            { "data": "statustitle" },
            { "data": "teamtitle" },
            { "data": "construction" },
            { "data": "contract" },
            { "data": "worked", searchable: false },
            { "data": "action", searchable: false, sortable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).find('td:eq(0)').html($('<div />').html(data.id).text());
            $(row).find('td:eq(8)').html($('<div />').html(data.contract).text());
            $(row).find('td:eq(9)').html($('<div />').html(data.worked).text());
            $(row).addClass(data.is_rush ? "row-is-rush success" : '').addClass(data.due_date < Math.round(new Date() / 1000) ? 'due-date-past danger' : '');
        },
        "order": [
            [0, "desc"]
        ],
        oLanguage: {
            sProcessing: 'Please wait - loading...'
        },
        processing: true,
        'pagingType': 'full_numbers_no_ellipses',
        "drawCallback": function() {
            $('tr').css('opacity', 1);
            $('.mark-as-reviewed, .mark-as-unreviewed').on('click', function() {
                var $id = $(this).attr('data-id');
                if (!$id) {
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: 'purchase-pipeline/mark-as-reviewed',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $id
                    }
                });
                $pendingTable.api().ajax.reload();
            });
            $('.mark-as-worked').on('click', function() {
                var $id = $(this).attr('data-id');

                if (!$id) {
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: 'purchase-pipeline/mark-as-worked',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $id
                    }
                });
                $pendingTable.api().ajax.reload();
            });
            $('.mark-as-requested').on('click', function() {
                var $id = $(this).attr('data-id');

                if (!$id) {
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: 'purchase-pipeline/mark-as-requested',
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

    $('input[name="due_date"]').daterangepicker({
        "singleDatePicker": true,
        autoUpdateInput: false,
        locale: {
            format: 'MM/DD/YYYY'
        }
    }, function(start, end, label) {});

    $('input[name="due_date"]').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY'));
        $pendingTable.api().ajax.reload();
    });
});