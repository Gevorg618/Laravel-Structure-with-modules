 $(document).ready(function() {

    // var nowDate = new Date(); 
    var date = $("#query_date").val();

    document.getElementById("current_time").innerHTML = date.toString();

    $.ajaxSetup({
        header:$('meta[name="_token"]').attr('content')
    })

    $.ajax({

        type: "GET",
        url: '/admin/statistics-user-tracking/big-statistics/show',
        data: {date:date},
        dataType: 'json',
        success: function(data){

            Morris.Donut({
              element: 'orders-placed',
              data: [
                {label: "Placed", value: data.placedOrder}
              ]
            });
            Morris.Donut({
              element: 'orders-assigned',
              data: [
                {label: "Assigned", value: data.assignedOrders}
              ]
            });
            Morris.Donut({
              element: 'orders-completed',
              data: [
                {label: "Completed", value: data.completedOrder}
              ]
            });
            Morris.Donut({
              element: 'orders-canceled',
              data: [
                {label: "Canceled", value: data.canceledOrders}
              ]
            });

            Morris.Line({
              element: 'all-orders',
              hideHover: false,
              data: data.lineChartsData,
              xkey: 'datetime',
              ykeys: ['a', 'b', 'c', 'd'],
              labels: ['Placed', 'Assigned', 'Completed', 'Canceled']
            });

            Morris.Bar({
              element: 'team-orders',
              hideHover: false,
              data: data.barChartsData,
              xkey: 'teamName',
              ykeys: ['a', 'b', 'c', 'd'],
              labels: ['Placed', 'Assigned', 'Completed', 'Canceled']
            });
          
        },
        error: function(data){

        }
    })

    $('#counter_number').html($refreshIn);
    setInterval("refreshCounter();", 1000);
});

var $refreshIn = 60;
var $counter = $refreshIn;
var $doRefresh = false;

function refreshCounter() {
    $counter--;
    if($doRefresh) {
        return false;
    }
    if($counter <= 0) {
        $doRefresh = true;
        location.reload();
        return false;
    }

    // Update
    $('#counter_number').html($counter);
}
