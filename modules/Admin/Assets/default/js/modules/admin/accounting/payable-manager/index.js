/* eslint-disable */
function notificationErrorMessage(message) {
    toastr['error'](message, 'error'.toUpperCase());
};

function notificationSuccessMessage(message) {
    toastr['success'](message, 'success'.toUpperCase());
};

function notificationWarningMessage(message) {
    toastr['warning'](message, 'warning'.toUpperCase());
};

$(function() {
    $('input[name="daterange"]').daterangepicker();
    $('.multiselect').selectpicker();
});

$(document).on('change', '#checked_all', function() {

    var request_type = $("#request_type").val();

    if(this.checked) {
        $('#'+request_type +' .record_id_checkbox').prop('checked', true);
        countItems();
        $("#count_records").html($checked);
    } else {
        showRevert(false);
        $('#'+request_type+ ' .record_id_checkbox').prop('checked', false);
    }
});

sum = 0;
$(document).on('change', '.record_id_checkbox', function() {
    var checkData = $(this).attr('data-check');
    var substr =  checkData.substring('1').replace(',', '');
    sum = sum + Number(substr);
    $("#total").html("Total amount"+ " "+ '$'+sum)
    countItems();
    $("#count_records").html($checked);

});

function showRevert(checked){

    if (checked) {
        $('.danger-zone-revert').removeClass('hidden');
    } else {
        $('.danger-zone-revert').addClass('hidden');
    }

};



$checked = 0;

countItems = function() {

    $checked = 0;

    var request_type = $("#request_type").val();

    $('#'+request_type +' .record_id_checkbox').each(function() {
        if($(this).is(':checked')) {
            $checked++;
        }
    });

    var records = $(".record_id_checkbox:checked").map(function() {return $(this).val();}).get();

    $('#checked_items').val(records);

    if ($checked == 0 ) {
        showRevert(false);
    } else {
        showRevert(true);
    }
};

$(document).on("click", ".apply-payment", function() {

    var $request_type = $("#request_type").val();

    var $records = $(".record_id_checkbox:checked").map(function() {return $(this).val();}).get();

    $('#loading').show();

    $.ajax({
      url: '/admin/accounting/payable-manager/apply-payment',
      data: {'request_type': $request_type, 'records': $records},
      dataType: 'json',
      type: 'POST',
      success: function(data) {

            $('#checked_all').prop('checked', false);
            showRevert(false);
            if (data.items.success) {
                // location.href = '/accounting/payable-reports/'+data.file_name+'.xlsx';
                location.href = '/admin/accounting/payable-manager/read?file=' + data.file_name;
                // $("#show_button" ).trigger("click");
                notificationSuccessMessage(data.message);
                $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
            } else {
                // $("#show_button" ).trigger("click");
                notificationErrorMessage(data.message);
            }
      }
    });
    $('#loading').hide();
});

$(document).ready(function() {

    $('#show_button').on('click',function(e) {
        e.preventDefault(e);
        $('#loading').show();



        var form = $('#form-report-show').serializeArray();

        var request_type = $("#request_type").val();

        var data = {
            daterange : $('#daterange').val(),
            client : $('#client').val(),
            status : $('#status').val(),
            states : $('#states').val(),
            balance : $('#balance').val(),
            free_text : $('#free_text').val(),
            request_type : request_type
        };

        var requestData = {
            url: '/admin/accounting/payable-manager/data',
            data: {formData: data},
            type: 'POST'
        };

        $("#callendar-orders").addClass("hidden")

        showRevert(false);
        switch (request_type) {

                case 'apprasial':

                        $app.datatables('#apprasial-datatable', requestData, {
                            columns: [
                                {data: 'checkbox', 'orderable': false, 'targets': 0},
                                {data: 'uid', 'orderable': false, 'targets': 0},
                                {data: 'name', 'orderable': false, 'targets': 0},
                                {data: 'payee_name', 'orderable': false, 'targets': 0},
                                {data: 'address', 'orderable': false, 'targets': 0},
                                {data: 'city', 'orderable': false, 'targets': 0},
                                {data: 'state', 'orderable': false, 'targets': 0},
                                {data: 'zip', 'orderable': false, 'targets': 0},
                                {data: 'ein', 'orderable': false, 'targets': 0},
                                {data: 'w9', 'orderable': false, 'targets': 0},
                                {data: 'check_amount', 'orderable': false, 'targets': 0},
                                {data: 'check_number', 'orderable': false, 'targets': 0},
                                {data: 'pay_date', 'orderable': false, 'targets': 0},
                                {data: 'date_delivered', 'orderable': true, 'targets': 0},
                                {data: 'id', 'orderable': true, 'targets': 0},
                                {data: 'status', 'orderable': false, 'targets': 0},
                                {data: 'address1', 'orderable': false, 'targets': 0},
                                {data: 'split_amount', 'orderable': false, 'targets': 0},
                                {data: 'balance', 'orderable': false, 'targets': 0}
                            ],
                            iDisplayLength: 75,
                            lengthMenu: [ 1, 25, 50, 75, 150, 300, 500 ],
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false,
                            responsive: false,
                            "processing": false,
                            createdRow: function ( row, data, index ) {
                                if ( !data['count'] ) {
                                    $('td', row).addClass('danger');
                                }
                            },
                            infoCallback : function( settings, start, end, max, total, pre ) {
                                if (total > 0 ) {
                                    $('#download_csv').removeClass('hidden');
                                } else {
                                    $('#download_csv').addClass('hidden');
                                }
                            
                                return pre;
                            }
                        });
                   break;
                case 'alt':
                        $app.datatables('#alt-datatable', requestData, {
                            columns: [
                                {data: 'checkbox', 'orderable': false, 'targets': 0},
                                {data: 'uid', 'orderable': false, 'targets': 0},
                                {data: 'name', 'orderable': false, 'targets': 0},
                                {data: 'payee_name', 'orderable': false, 'targets': 0},
                                {data: 'address', 'orderable': false, 'targets': 0},
                                {data: 'city', 'orderable': false, 'targets': 0},
                                {data: 'state', 'orderable': false, 'targets': 0},
                                {data: 'zip', 'orderable': false, 'targets': 0},
                                {data: 'ein', 'orderable': false, 'targets': 0},
                                {data: 'w9', 'orderable': false, 'targets': 0},
                                {data: 'check_amount', 'orderable': false, 'targets': 0},
                                {data: 'check_number', 'orderable': false, 'targets': 0},
                                {data: 'pay_date', 'orderable': false, 'targets': 0},
                                {data: 'submitted', 'orderable': true, 'targets': 0},
                                {data: 'id', 'orderable': true, 'targets': 0},
                                {data: 'status', 'orderable': false, 'targets': 0},
                                {data: 'address1', 'orderable': false, 'targets': 0},
                                {data: 'split_amount', 'orderable': false, 'targets': 0},
                                {data: 'balance', 'orderable': false, 'targets': 0}
                            ],
                            iDisplayLength: 75,
                            lengthMenu: [ 50, 75, 150, 300, 500 ],
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false,
                            responsive: false,
                            "processing": false,
                            createdRow: function ( row, data, index ) {
                                if ( !data['count']) {
                                    $('td', row).addClass('danger');
                                }
                            },
                            infoCallback : function( settings, start, end, max, total, pre ) {
                                if (total > 0 ) {
                                    $('#download_csv').removeClass('hidden');
                                } else {
                                    $('#download_csv').addClass('hidden');
                                }

                                return pre;
                            }
                        });
                   break;
                case 'trimerge':
                        $app.datatables('#trimerge-datatable', requestData, {
                            columns: [
                                {data: 'checkbox', 'orderable': false, 'targets': 0},
                                {data: 'uid', 'orderable': false, 'targets': 0},
                                {data: 'name', 'orderable': false, 'targets': 0},
                                {data: 'payee_name', 'orderable': false, 'targets': 0},
                                {data: 'address', 'orderable': false, 'targets': 0},
                                {data: 'city', 'orderable': false, 'targets': 0},
                                {data: 'state', 'orderable': false, 'targets': 0},
                                {data: 'zip', 'orderable': false, 'targets': 0},
                                {data: 'ein', 'orderable': false, 'targets': 0},
                                {data: 'w9', 'orderable': false, 'targets': 0},
                                {data: 'check_amount', 'orderable': false, 'targets': 0},
                                {data: 'check_number', 'orderable': false, 'targets': 0},
                                {data: 'pay_date', 'orderable': false, 'targets': 0},
                                {data: 'submitted', 'orderable': true, 'targets': 0},
                                {data: 'id', 'orderable': true, 'targets': 0},
                                {data: 'status', 'orderable': false, 'targets': 0},
                                {data: 'address1', 'orderable': false, 'targets': 0},
                                {data: 'split_amount', 'orderable': false, 'targets': 0},
                                {data: 'balance', 'orderable': false, 'targets': 0}
                            ],
                            iDisplayLength: 75,
                            lengthMenu: [ 50, 75, 150, 300, 500 ],
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false,
                            responsive: false,
                            "processing": false,
                            createdRow: function ( row, data, index ) {
                                if ( !data['count']) {
                                    $('td', row).addClass('danger');
                                }
                            },
                            infoCallback : function( settings, start, end, max, total, pre ) {
                                if (total > 0 ) {
                                    $('#download_csv').removeClass('hidden');
                                } else {
                                    $('#download_csv').addClass('hidden');
                                }

                                return pre;
                            }
                        });
                   break;
                case 'fees':
                        $app.datatables('#fees-datatable', requestData, {
                            columns: [
                                {data: 'checkbox', 'orderable': false, 'targets': 0},
                                {data: 'uid', 'orderable': false, 'targets': 0},
                                {data: 'name', 'orderable': false, 'targets': 0},
                                {data: 'payee_name', 'orderable': false, 'targets': 0},
                                {data: 'address', 'orderable': false, 'targets': 0},
                                {data: 'city', 'orderable': false, 'targets': 0},
                                {data: 'state', 'orderable': false, 'targets': 0},
                                {data: 'zip', 'orderable': false, 'targets': 0},
                                {data: 'ein', 'orderable': false, 'targets': 0},
                                {data: 'w9', 'orderable': false, 'targets': 0},
                                {data: 'check_amount', 'orderable': false, 'targets': 0},
                                {data: 'check_number', 'orderable': false, 'targets': 0},
                                {data: 'pay_date', 'orderable': false, 'targets': 0},
                                {data: 'deliver_date', 'orderable': false, 'targets': 0},
                                {data: 'id', 'orderable': true, 'targets': 0},
                                {data: 'status', 'orderable': false, 'targets': 0},
                                {data: 'address1', 'orderable': false, 'targets': 0},
                                {data: 'split_amount', 'orderable': false, 'targets': 0},
                                {data: 'balance', 'orderable': false, 'targets': 0}
                            ],
                            iDisplayLength: 75,
                            lengthMenu: [ 50, 75, 150, 300, 500 ],
                            retrieve: false,
                            destroy: true,
                            searchable: false,
                            searching: false,
                            responsive: false,
                            "processing": false,
                            createdRow: function ( row, data, index ) {
                                if ( !data['count']) {
                                    $('td', row).addClass('danger');
                                }
                            },
                            infoCallback : function( settings, start, end, max, total, pre ) {
                                if (total > 0 ) {
                                    $('#download_csv').removeClass('hidden');
                                } else {
                                    $('#download_csv').addClass('hidden');
                                }

                                return pre;
                            }
                        });
                   break;
               default:
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
        sum = 0;
        var target = $(e.target).attr("data-type"); // activated tab
        $("#request_type").val(target);
        $('#show_button').trigger('click');

        // alert (target);
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();
    } );
});

$( document ).ajaxStop(function() {
    $('#loading').remove();
});
