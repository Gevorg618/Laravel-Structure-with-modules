$(function () {
    $(document).on("click","#all_amount_button",function() {
        
        var stateAbbr = $(this).attr('data-state-abbr');
        var amount = $("#"+stateAbbr+"_all_amount").val();
        $('.'+stateAbbr+'_all_amount').val(amount);
    });

    $(document).on("click","#all_fhaamount_button",function() {
        var stateAbbr = $(this).attr('data-state-abbr');
        var fhaAmount = $("#"+stateAbbr+"_all_fha_amount").val();
        $('.'+stateAbbr+'_all_fhaamount').val(fhaAmount);
    });

    $(document).on("click","#all_fee_type_button",function() {
        var stateAbbr = $(this).attr('data-state-abbr');
        var feeType = $( "#"+stateAbbr+"_all_fee_type option:selected" ).val();

        $.each($('.'+stateAbbr+'_all_fee_type'), function(i, item) {
            $(this).val(feeType).trigger('change');
        });
    });  
})