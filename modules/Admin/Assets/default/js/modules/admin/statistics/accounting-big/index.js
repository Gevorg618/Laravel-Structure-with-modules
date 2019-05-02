 $(document).ready(function() {

    var nowDate = new Date(); 
    var date = nowDate.getFullYear()+'/'+(nowDate.getMonth()+1)+'/'+nowDate.getDate(); 
    document.getElementById("current_time").innerHTML = date.toString();

    $.ajaxSetup({
        header:$('meta[name="_token"]').attr('content')
    })

    $.ajax({

        type: "GET",
        url: '/admin/statistics-user-tracking/accounting-big-statistics/show',
        dataType: 'json',
        success: function(data){

            $('#assigned-count').html(data.assignedOrders);
            $('#completed-count').html(data.completedOrder);
            $('#delivered-invoiced').html(data.deliveredInvoiceTotal);
            $('#delivered-paid').html(data.deliveredPaidTotal);
            $('#delivered-margin').html(data.totalMargin);
            $('#percent-collected').html(data.percentCollected);

            $("#credit-card").html(data.accountingStatsDeliveredCC);
            $("#credit-card-percent").html('('+data.deliveredCreditCardPercent+'%)');
            
            $("#delivered-check").html(data.accountingStatsDeliveredCheck);
            $("#delivered-check-percent").html('('+data.deliveredCheckPercent+'%)');

            $("#delivered-invoiced-count").html(data.accountingStatsDeliveredInvoice);
            $("#delivered-invoiced-count-percent").html('('+data.deliveredInvoicePercent+'%)');

            $("#delivered-cod").html(data.accountingStatsDeliveredCOD);
            $("#delivered-cod-percent").html('('+data.deliveredCODPercent+'%)');

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
            Morris.Donut({
              element: 'orders-qc',
              data: [
                {label: "QC", value: data.qcOrders}
              ]
            });

            Morris.Line({
              element: 'monthly-revenue',
              hideHover: false,
              preUnits: '$ ',
              data: data.monthlyRevenue,
              xkey: 'y',
              ykeys: ['a', 'b', 'c', 'd'],
              labels: ['Invoice', 'Paid', 'Split', 'Margin']
            });

            // Morris.Bar({
            //   element: 'outstanding-accounts-rec',
            //   hideHover: false,
            //   preUnits: '$ ',
            //   data: data.outstandingAccounts,
            //   xkey: 'y',
            //   ykeys: ['a', 'b', 'c', 'd'],
            //   labels: ['60 Days', '61-90 Days', '91-120 Days', '121+ Days']
            // });

            Morris.Line({
              element: 'daily-margin',
              hideHover: false,
              preUnits: '$ ',
              data:data.dailyMargin,
              xkey: 'y',
              ykeys: ['a', 'b', 'c'],
              labels: ['Margin', 'Invoice Amount', 'Split Amount']
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
