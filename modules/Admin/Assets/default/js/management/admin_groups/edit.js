$().ready(function() {

    // Mark Yes Tab
    $('.mark-yes-tab').on('click', function() {
        var $div = $(this).parent();
        $.each($('select', $div), function() {
            $(this).val('1');
        });
    });

    // Mark no Tab
    $('.mark-no-tab').on('click', function() {
        var $div = $(this).parent();
        $.each($('select', $div), function() {
            $(this).val('0');
        });
    });

    // Mark Yes Head
    $('.mark-yes-head').on('click', function() {
        var $div = $(this).parent();
        $.each($('select', $div), function() {
            $(this).val('1');
        });
    });

    // Mark no Tab
    $('.mark-no-head').on('click', function() {
        var $div = $(this).parent();
        $.each($('select', $div), function() {
            $(this).val('0');
        });
    });
});
