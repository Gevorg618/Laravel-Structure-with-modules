$(function() {
    $('input[name="daterange"]').daterangepicker();
    $('.multiselect').selectpicker();
});

$(document).on("click",".show-report",function() {
    
    $('.report-list-show').removeClass('hidden');
    
    var data = $('#form-report-show').serializeArray();
    
    var client = $('.client').val();
    var daterange = $('.daterange').val();
    var date_type = $('.date_type').val();
    var report_type = $('.report_type').val();

    var clients = [];


    $('.client').map(function(idx, elem) {
         clients[idx] = $(elem).val();
    }).get();


    var requestData = {
        type: 'POST',
        url: '/admin/manager-reports/client-settings/data',
        data: {daterange: daterange , date_type:date_type, report_type: report_type, client:clients[1]}
    };

    $app.datatables('#report-result-datatable', requestData, {
        columns: [
             {data: 'client'},
             {data: 'team'},
             {data: 'created_by'},
             {data: 'created_date'},
             {data: 'note', width: "40%" }
        ],
        iDisplayLength: 25,
        lengthMenu: [ 10, 25, 50, 75, 100 ],
        order : false,
        orderable: false,
        retrieve: false,
        destroy: true,
        searchable: false,
        searching: false,
        ordering: false
    });
});