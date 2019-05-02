$().ready(function() {
    $('.realview-submit').on('click', function() {
        var $type = $(this).data('type');

        $('.new-realview-submission-errors, .new-realview-submission-success').addClass('hidden');

        // Run ajax to submit the XML Document
        $.ajax({
            url: '/admin/post-completion-pipelines/appr-uw-pipeline/check-real-view-submit',
            method:'post',
            data: {
                'action': 'realview-submit',
                'id': $orderId,
                'type': $type
            },
            dataType: 'json'
        }).done(function(data) {
            $('.new-realview-submission-success').removeClass('hidden');
            $('.new-realview-submission-success').html(data.html);

            // Show the in progress message
            $('.realview-progress-bar').removeClass('hidden');

            // Disable the buttons
            $('.realview-submit').attr('disabled', true);

            if(data.realView) {
                $realView = data.realView;
                $inProcess = true;
            }
        }).fail(function(data) {
            $('.new-realview-submission-errors').removeClass('hidden');
            $('.new-realview-submission-errors').html(data.responseText);
        }).always(function() {

        });
    });

    // Progress Bar start interval
    $app.checkRealView = function() {
        if(!$inProcess) {
            return false;
        }

        // Run ajax to submit the XML Document
        $.ajax({
            url: '/admin/post-completion-pipelines/appr-uw-pipeline/check-real-view-submit',
            data: {
                'action': 'check-realview-submit',
                'id': $orderId
            },
            dataType: 'json'
        }).done(function(data) {


            if(data.realView.is_processed>0) {
                // Show the in progress message
                $('.realview-progress-bar').addClass('hidden');
                $('.realview-result-div').html(data.html);
                $inProcess = false;
            }
        }).fail(function(data) {
            console.log(data);
        }).always(function() {

        });
    };


    setInterval(function() {
        $app.checkRealView();
    }, 5000);
});