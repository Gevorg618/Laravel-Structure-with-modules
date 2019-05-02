function notificationErrorMessage(message) {
    toastr['error'](message, 'error'.toUpperCase());
};

function notificationSuccessMessage(message) {
    toastr['success'](message, 'success'.toUpperCase());
};

function drawChart() {
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Apprasial Name');
    data.addColumn('number', 'Completed');
    data.addColumn('number', 'Placed');

    google.visualization.arrayToDataTable([
      ['x', 'Placed', 'Completed'],
    ]);

    $.each(dataCharts, function (typeName, value) {
        data.addRows([[String(typeName), parseInt(value.completed), parseInt(value.created)],]);
    });

    var options = {
      curveType: 'function',
      legend: { position: 'bottom' },
      width: 1200,
      height: 700,
      chartArea: {left:80,right:90,top:20,bottom:300,width:"100%",height:"100%"},
      hAxis: {slantedText: true, allowContainerBoundaryTextCufoff: true, slantedTextAngle:75}
    };

    var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

    chart.draw(data, options);
}

 $(document).ready(function() {

    $('#show_button').on('click',function(e){
        

        var date_from = $('#date_from').val();
        var date_to = $('#date_to').val();
        var clients = $('#clients').val();
        var request_type = $("#request_type").val();

        e.preventDefault(e);

        var requestData = {
            url: '/admin/statistics-user-tracking/statistics/show',
            data: {date_from:date_from, date_to:date_to, clients:clients, request_type:request_type}
        };
        $("#callendar-orders").addClass("hidden")
        $("#nav-content").removeClass('hidden');
        
        switch (request_type) {

                case 'placed':

                        $app.datatables('#placed-datatable', requestData, {
                            columns: [
                                 {data: 'orderedate'},
                                 {data: 'company'},
                                 {data: 'user'},
                                 {data: 'appr_type'},
                                 {data: 'address'},
                                 {data: 'state'},
                                 {data: 'payment_status'},
                                 {data: 'invoice_amount'},
                                 {data: 'split_amount'},
                                 {data: 'margin'}
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
                case 'assigned':

                        $app.datatables('#assigned-datatable', requestData, {
                            columns: [
                                 {data: 'orderedate'},
                                 {data: 'company'},
                                 {data: 'appr_type'},
                                 {data: 'address'},
                                 {data: 'state'},
                                 {data: 'payment_status'},
                                 {data: 'invoice_amount'},
                                 {data: 'split_amount'},
                                 {data: 'margin'},
                                 {data: 'engager'},
                                 {data: 'team'}
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
                case 'low_margin':
                        $app.datatables('#low-margin-datatable', requestData, {
                            columns: [
                                 {data: 'orderedate'},
                                 {data: 'company'},
                                 {data: 'user'},
                                 {data: 'appr_type'},
                                 {data: 'address'},
                                 {data: 'state'},
                                 {data: 'payment_status'},
                                 {data: 'invoice_amount'},
                                 {data: 'split_amount'},
                                 {data: 'margin'},
                                 {data: 'engager'}
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
                case 'completed':

                        $app.datatables('#completed-datatable', requestData, {
                            columns: [
                                {data: 'orderedate'},
                                {data: 'company'},
                                {data: 'user'},
                                {data: 'appr_type'},
                                {data: 'address'},
                                {data: 'state'},
                                {data: 'payment_status'},
                                {data: 'invoice_amount'},
                                {data: 'split_amount'},
                                {data: 'margin'},
                                {data: 'total_turn_time'}
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
                case 'canceled':

                        $app.datatables('#canceled-datatable', requestData, {
                            columns: [
                                {data: 'orderedate'},
                                {data: 'company'},
                                {data: 'user'},
                                {data: 'appr_type'},
                                {data: 'address'},
                                {data: 'state'},
                                {data: 'payment_status'},
                                {data: 'invoice_amount'},
                                {data: 'split_amount'},
                                {data: 'margin'},
                                {data: 'team'}
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
                case 'charts':
                        $.ajax({
                          type:'GET',
                          url:'/admin/statistics-user-tracking/statistics/show',
                          data: {date_from:date_from, date_to:date_to, clients:clients, request_type:request_type},
                          success:function(data){

                                window.dataCharts = data.chartsData;

                                // chart
                                google.charts.load('current', {'packages':['corechart']});
                                google.charts.setOnLoadCallback(drawChart);
                                  
                         }

                        });
                        
                   break;
               default:
                    $("#nav-content").removeClass('hidden');

                    $($.fn.dataTable.tables( true ) ).css('width', '100%');
                    $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();   
                    break;
        }

                
    });
});


$(document).on("click", "#reset", function() { 
    $("#nav-content").addClass('hidden');
    $("#callendar-orders").removeClass("hidden");
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