$(document).on('click', '.show_orders', function() {
   
    var $records = $(".record_id_checkbox:checked").map(function() {
        return $(this).val();
    }).get();
    window.location.href =  '/admin/accounting/docuvault-receivables/show?'+$records.join('&');
});

$(document).on('change', '#checked_all', function() {
   
    if(this.checked) {
        $('.record_id_checkbox').prop('checked', true);
        countItems();
        $("#count_records").html($checked);           
        $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    } else {
        showRevert(false);
        $('.record_id_checkbox').prop('checked', false);
    }
});

$(document).on('change', '.record_id_checkbox', function() {
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

    $('.record_id_checkbox').each(function() {
        if($(this).is(':checked')) {
            $checked++;
        }
    });

    if ($checked == 0 ) {
        showRevert(false);
        
    } else {
        showRevert(true);
        
    }
};