$(function() {
    $('.toggle-content').click(function() {
        var $button = $(this);
        var $panel = $(this).parent().parent().parent();
        if($panel.find('.full-log-content').hasClass('hidden')) {
            var $id = $(this).data('id');
            if(!$id) {
                $panel.find('.full-log-content').html('LOG ID WAS NOT FOUND.');
                return false;
            }
            $.ajax({
                url: '/admin/integrations/api-users/log-content/' + $id,
                dataType: 'json',
                success: function(data) {
                    $panel.find('.full-log-content').html(data.html);
                    $panel.find('.full-log-content').removeClass('hidden');
                    $button.find('span').html('Hide');
                }
            });
        } else {
            $panel.find('.full-log-content').addClass('hidden');
            $panel.find('.full-log-content').html('');
            $(this).find('span').html('Show');
        }
    });

    var minDate;
    $('.date_from').daterangepicker({
        "singleDatePicker": true,
        "startDate": new Date(),
        locale: {
            format: 'MM/DD/YYYY'
        },
    }, function(start, end, label) {
        minDate = start;
        endDate();
    });

    var endDate = function() {
        $('.date_to').daterangepicker({
            "singleDatePicker": true,
            'minDate': minDate,
            locale: {
                format: 'MM/DD/YYYY'
            }
        }, function(start, end, label) {});
    }
    endDate();
    $('.date_to').val('');
    $('.date_from').val('');
});
