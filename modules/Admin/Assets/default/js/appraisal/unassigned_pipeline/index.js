$(document).ready(function() {
    $pendingTable = $('#datatable').dataTable({
        "serverSide": true,
        stateSave: true,
        ajax: {
            type: "POST",
            url: 'unassigned-pipeline/data',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: function(d) {
                $('tr').css('opacity', 0.8);
                var filters = {
                    'is_rush': $('#is_rush').val(),
                    'team': $('#team').val(),
                    'client': $('#client').val(),
                    'due_date': $('#due_date').val(),
                    'state': $('#state').val(),
                    'timezone': $('#timezone').val(),
                    'loanreason': $('#loanreason').val(),
                    'autoselect': $('#autoselect').val(),
                    'unassigned-pipeline': 1,
                };
                d.filters = filters;
            }
        },
        "columns": [
            { "data": "title" },
            { "data": "address" },
            { "data": "client" },
            { "data": "assigned_date" },
            { "data": "appraisal" },
            { "data": "worked", searchable: false },
            { "data": "total_invites", searchable: false },
            { "data": "tickets", searchable: false, sortable: false },
            { "data": "actions", searchable: false, sortable: false }
        ],
        createdRow: function(row, data, dataIndex) {
            $(row).find('td:eq(0)').html($('<div />').html(data.title).text());
            $(row).find('td:eq(3)').html($('<div />').html(data.assigned_date).text());
            $(row).find('td:eq(5)').html($('<div />').html(data.worked).text());
            $(row).find('td:eq(8)').html($('<div />').html(data.actions).text());
        },
        "order": [
            [3, "asc"]
        ],
        "pagingType": "full_numbers",
        "oLanguage": {
            "sProcessing": 'Please wait - loading...'
        },
        processing: true,
        'pagingType': 'full_numbers_no_ellipses',
        "drawCallback": function() {
            $('tr').css('opacity', 1);
            $('.mark-as-worked').on('click', function() {
                var $id = $(this).attr('data-id');
                if (!$id) {
                    return false;
                }
                $.ajax({
                    type: "POST",
                    url: 'unassigned-pipeline/mark-as-reviewed',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $id
                    }
                });
                $pendingTable.api().ajax.reload();
            });

            $('.mark-priority').on('click', function() {
                var $id = $(this).attr('data-id');
                var $priority = $(this).attr('data-priority');

                if (!$id) {
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: 'unassigned-pipeline/mark-priority',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        'id': $id,
                        'priority': $priority
                    }
                });
                $pendingTable.api().ajax.reload();
            });
        }
    });

    $('.filter-change').on('change', function() {
        $pendingTable.api().ajax.reload();
    });

    $('#refresh_button').click(function() {
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
    createBootstrapSelect();
    createBootstrapSelectSmall();
    createBootstrapSelectUp();
});

function createBootstrapSelect() {
    $(".bootstrap-multiselect").multiselect({
        enableFiltering: true,
        filterBehavior: 'both',
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        }
    });
}

function createBootstrapSelectSmall() {
    $(".bootstrap-multiselect-small").multiselect({
        enableFiltering: true,
        filterBehavior: 'both',
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 100,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        }
    });
}

function createBootstrapSelectUp() {
    $(".bootstrap-multiselect-up").multiselect({
        enableFiltering: true,
        filterBehavior: 'both',
        numberDisplayed: 1,
        enableCaseInsensitiveFiltering: true,
        maxHeight: 400,
        templates: {
            divider: '<div class="divider" data-role="divider"></div>'
        },
        buttonContainer: '<div class="btn-group dropup" />'
    });
}