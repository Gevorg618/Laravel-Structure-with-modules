$(function() {
    // Clicked View
    $(document).on('click', '.view-mail-row', function() {
        var $id = $(this).attr('data-id');
        // Get info
        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/view-row/" + $id,
            success: function(data) {
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $('#view_mail_record').html(data);
                $('#view_mail_record').modal();
            }
        });
    });

    // Check all
    $(document).on('click', '#check-all-labels', function() {
        if ($(this).is(':checked')) {
            $('.label-checkbox').prop('checked', true);
        } else {
            $('.label-checkbox').prop('checked', false);
        }
    });

    // Check by clicking on row
    $(document).on('click', 'tr.item-row', function(e) {
        if (e.target.nodeName == 'TD') {
            var $id = $(this).attr('id');
            $('#label_' + $id).prop('checked', !$('#label_' + $id).is(':checked'));
        }
    });

    // Print selected shipping labels
    $(document).on('click', '#print-selected-shipping-labels', function() {
        var $ids = $('.label-checkbox:checked').map(function() { return this.value; }).get().join(',');
        if (!$ids.length) {
            return false;
        }
        var win = window.open('/admin/post-completion-pipelines/mail-pipeline/create-pdf-label?ids=' + $ids, '_blank');
        win.focus();
    });

    // Mark ready to mail
    $(document).on('click', '#mark-ready-to-mail', function() {
        // Disable button and add overlay
        $(this).prop('disabled', true).LoadingOverlay('show', '#view_mail_record_content');
        $id = $(this).attr('data-id');

        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/mark-ready-to-mail/" + $id,
            data: { 'id': $id },
            dataType: 'json',
            success: function(data) {
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $(this).prop('disabled', false).LoadingOverlay('hide', '#view_mail_record_content');
                $('#view_mail_record').modal('hide');
                location.reload();
            }
        });
    });

    // Mark as failed
    $(document).on('click', '#mark-manual-failed', function() {
        // Disable button and add overlay
        $(this).prop('disabled', true).LoadingOverlay('show', '#view_mail_record_content');
        $id = $(this).attr('data-id');

        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/do-mark-failed/" + $id,
            data: { 'id': $id },
            dataType: 'json',
            success: function(data) {
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $(this).prop('disabled', false).LoadingOverlay('hide', '#view_mail_record_content');
                $('#view_mail_record').modal('hide');
                location.reload();
            }
        });
    });

    // Mark as delivered
    $(document).on('click', '#mark-manual-delivered', function() {
        // Disable button and add overlay
        $(this).prop('disabled', true).LoadingOverlay('show', '#view_mail_record_content');
        $id = $(this).attr('data-id');

        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/do-mark-delivered/" + $id,
            data: { 'id': $id },
            dataType: 'json',
            success: function(data) {
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $(this).prop('disabled', false).LoadingOverlay('hide', '#view_mail_record_content');
                $('#view_mail_record').modal('hide');
                location.reload();
            }
        });
    });

    // Edit Tracking number
    $(document).on('click', '.edit-row-tracking-number', function() {
        $id = $(this).attr('data-id');

        // Get info
        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/edit-tracking-number/" + $id,
            data: { 'id': $id },
            success: function(data) {
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $('#edit_tracking_number').html(data);
                $('#edit_tracking_number').modal();
            }
        });
    });

    // Do save tracking number
    $(document).on('click', '#do_edit_tracking_number', function() {
        var $id = $('#rowid').val();
        var $trackingNumber = $.trim($('#tracking_number').val());
        $('#log_ok_block, #log_error_block').hide();
        if (!$id) {
            $('#log_error_block').html('Sorry, That record was not found.');
            $('#log_error_block').show();
            return false;
        }
        // validate
        if (!$trackingNumber) {
            $('#log_error_block').html('Sorry, You must enter a tracking number.');
            $('#log_error_block').show();
            return false;
        }
        // Disable submit
        $('#do_edit_tracking_number').html('Loading...');
        $('#do_edit_tracking_number').attr('disabled', true);

        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/do-save-tracking-number/",
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: { 'id': $id, 'trackingNumber': $trackingNumber },
            dataType: 'json',
            success: function(data) {
                // Disable submit
                $('#do_edit_tracking_number').html('Submit');
                $('#do_edit_tracking_number').removeAttr('disabled');

                if (data.error && data.error != '') {
                    $('#log_error_block').html(data.error);
                    $('#log_error_block').show();
                    return;
                }
                $('#log_ok_block').html(data.html);
                $('#log_ok_block').show();
                // Close modal window and refresh
                $('#edit_tracking_number').modal('hide');
                location.reload();
            }
        });
    });

    // Clicked Send
    $(document).on('click', '.mark-sent', function() {
        $id = $(this).attr('data-id');
        // Get info
        $.ajax({
            url: "/admin/post-completion-pipelines/mail-pipeline/mark-sent-form/" + $id,
            data: { 'id': $id },
            success: function(data) {
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $('#mark_sent_mail_record').html(data);
                $('#mark_sent_mail_record').modal();
            }
        });
    });

    // Labels
    $(document).on('click', '.create-label-priority', function() {
        $id = $(this).attr('data-id');
        createLabel($id, 'priority');
    });

    $(document).on('click', '.create-label-express', function() {
        if (!confirm('Are you sure you would like to create an express label?')) {
            return false;
        }
        $id = $(this).attr('data-id');
        createLabel($id, 'express');
    });

    // Do mark sent
    $(document).on('click', '#do_mark_sent', function() {
        var $id = $('#rowid').val();
        var $trackingNumber = $.trim($('#tracking_number').val());
        if (!$id) {
            alert('Sorry, That record was not found.');
            return false;
        }
        // Get selected checkboxes
        var $files = [];
        $.each($('.file_checkbox'), function(i, item) {
            var $fileId = $(this).attr('id');
            if ($(this).is(':checked')) {
                $files.push($fileId);
            }
        });
        // validate
        if (!$trackingNumber) {
            alert('Sorry, You must enter a tracking number.');
            return false;
        }
        // Make sure files were selected
        if ($files.length == 0) {
            alert('Sorry, You must select at least one file.');
            return false;
        }
        // Disable submit
        $('#do_mark_sent').html('Loading...');
        $('#do_mark_sent').attr('disabled', true);

        $.ajax({
            url: '/admin/post-completion-pipelines/mail-pipeline/do-mark-sent',
            data: {
                'id': $id,
                'trackingNumber': $trackingNumber,
                'files': $files.join(',')
            },
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                // Disable submit
                $('#do_mark_sent').html('Submit');
                $('#do_mark_sent').removeAttr('disabled');
                if (data.error && data.error != '') {
                    alert(data.error);
                    return;
                }
                $('#log_ok_block').html(data.html);
                $('#log_ok_block').show('blind');
                // Close modal window and refresh
                $('#mark_sent_mail_record').modal('hide');
                location.reload();
            }
        });
    });
});

function createLabel(id, type) {
    if (!id || !type) {
        return false;
    }
    // Show loading as this might take a few seconds
    var that = this;
    $(that).prop('disabled', true);
    // Get info
    $.ajax({
        url: '/admin/post-completion-pipelines/mail-pipeline/create-label',
        type: "POST",
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: { 'id': id, 'type': type },
        dataType: 'json',
        success: function(data) {
            $(that).prop('disabled', false);
            if (data.error && data.error != '') {
                alert(data.error);
                return;
            }
            // Show download button and remove other buttons for now
            if (data.label) {
                $(that).prop('disabled', true);
                $(that).prop('disabled', false);
                $('.download-label').removeClass('hidden');
                // Set tracking number
                $('#tracking_number').val(data.trackingNumber);
            }
        }
    });
}