$(function () {
    $("#all_amount_button").click(function () {
        var amount = $("#all_amount").val();
        $('.amount').val(amount);
    });
    $("#all_fhaamount_button").click(function () {
        var fhaAmount = $("#all_fha_amount").val();
        $('.fhaamount').val(fhaAmount);
    });
})