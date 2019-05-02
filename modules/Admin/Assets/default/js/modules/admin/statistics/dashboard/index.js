$(document).ready(function() {
    $('#show_button').on('click',function(e) {

        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var request_type = $("#request_type").val();

        e.preventDefault(e);

        var requestData = {
            url: '/admin/statistics-user-tracking/dashborad-statistics/show',
            data: {date_from:date_from, date_to:date_to, request_type:request_type}
        };

        switch (request_type) {

                case 'team':

                        $app.datatables('#team-datatable', requestData, {
                            columns: [
                                 {data: 'team_title'},
                                 {data: 'worked_on'},
                                 {data: 'avg_adjusted'},
                                 {data: 'avg_total'},
                                 {data: 'adjusted'},
                                 {data: 'total'},
                                 {data: 'transferred'},
                                 {data: 'delayed'}
                            ],
                            iDisplayLength: 10,
                            lengthMenu: [ 10, 25, 50, 75, 100 ],
                            order : false,
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false
                        });
                   break;
                case 'users':
                        $app.datatables('#users-datatable', requestData, {
                            columns: [
                                 {data: 'name'},
                                 {data: 'team'},
                                 {data: 'worked_on'},
                                 {data: 'avg_adjusted'},
                                 {data: 'avg_total'},
                                 {data: 'adjusted'},
                                 {data: 'total'},
                                 {data: 'transferred'},
                                 {data: 'delayed'}
                            ],
                            iDisplayLength: 10,
                            lengthMenu: [ 10, 25, 50, 75, 100 ],
                            order : false,
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false
                        });
                   break;
                case 'transferred_orders':
                        $app.datatables('#transferred_orders-datatable', requestData, {
                            columns: [
                                 {data: 'id'},
                                 {data: 'address'},
                                 {data: 'date'},
                                 {data: 'team'},
                                 {data: 'from_user'},
                                 {data: 'to_user'}
                            ],
                            iDisplayLength: 10,
                            lengthMenu: [ 10, 25, 50, 75, 100 ],
                            order : false,
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false
                        });
                   break;
                case 'daily_stats':
                        $app.datatables('#daily_stats-datatable', requestData, {
                            columns: [
                                 {data: 'team'},
                                 {data: 'company_pipeline'},
                                 {data: 'status_select_count'},
                                 {data: 'to_work_on'}
                            ],
                            iDisplayLength: 10,
                            lengthMenu: [ 10, 25, 50, 75, 100 ],
                            order : false,
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false
                        });
                   break;   
               default:
                
                $($.fn.dataTable.tables( true ) ).css('width', '100%');
                $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();   
                break;
        }

        $("#nav-content").removeClass('hidden');
                
    });
});

$(document).on("click", "#reset", function() { 
    $("#nav-content").addClass('hidden');
});

$(document).ready(function() {

    $('a[data-toggle="tab"]').on( 'shown.bs.tab', function (e) {
        var target = $(e.target).attr("data-type"); // activated tab

        $("#request_type").val(target);
        $('#show_button').trigger('click');

        // alert (target);
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    } ); 
});