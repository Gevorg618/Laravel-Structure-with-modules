$(document).on('change', '.checked_all_orders', function() {

    if(this.checked) {
        $(this).parent().parent().parent().parent().find('.record_id_checkbox').prop('checked', true);
    } else {
        $('.record_id_checkbox').prop('checked', false);
    }
});

$(document).on('click', '.download_csv', function() {

    var checkedItems =  $('.record_id_checkbox:checkbox:checked');
    
    var checked = {};
    
    var hasItems = false;

    checkedItems.each(function (key, index) {
        hasItems = true;
        var itemId = $(this).attr('name');
        checked[itemId] =  $("input[name="+itemId+"]:checked").map(function(){
            return $(this).val();
        }).get();
    });
    
    if (!hasItems) {
        
        $('.record_id_checkbox').prop('checked', true);
        
        var checkedItems =  $('.record_id_checkbox:checkbox:checked');

        checkedItems.each(function (key, index) {
            hasItems = true;
            var itemId = $(this).attr('name');
            checked[itemId] =  $("input[name="+itemId+"]:checked").map(function(){
                return $(this).val();
            }).get();
        });
    }

    $('#chekced_data').val(JSON.stringify(checked));

    $('#data_form').submit();
});

$(document).on('click', '.download_stat', function() {

    var checkedItems =  $('.record_id_checkbox:checkbox:checked');
    
    var checked = {};
    
    var hasItems = false;

    checkedItems.each(function (key, index) {
        var hasItems = true;
        var itemId = $(this).attr('name');
        checked[itemId] =  $("input[name="+itemId+"]:checked").map(function(){
            return $(this).val();
        }).get();
    });

    if (!hasItems) {
        
        $('.record_id_checkbox').prop('checked', true);
        
        var checkedItems =  $('.record_id_checkbox:checkbox:checked');

        checkedItems.each(function (key, index) {
            hasItems = true;
            var itemId = $(this).attr('name');
            checked[itemId] =  $("input[name="+itemId+"]:checked").map(function(){
                return $(this).val();
            }).get();
        });
    }

    $('#chekced_data_stat').val(JSON.stringify(checked));

    $('#data_statments').submit();
});
