$(function () {
    $('textarea.editor').ckeditor({
        width: '100%',
        height: '300px',
        toolbar: 'Full',
        filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl: '/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl: '/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });

    // All
    $count['all'] = $('.tr-rule').length;
    $('.count-all').html($count['all']);
    toggleSelectedVisibilityAndCount();
    updateTimers();
    buildCorrectionList();

    $('button[data-toggle=popover]').popover({html: true, trigger: 'focus'});

    // Toggle content visibility
    $('.toggle-content').click(function () {
        var $elem = $($(this).data('class'));
        if ($elem.hasClass('hidden')) {
            $elem.removeClass('hidden')
        } else {
            $elem.addClass('hidden')
        }
    });

    // Capture enter event for certain fields to prevent form submission
    $('.checklist-section-row input').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            return false;
        }
    });
    $app.attachmentSelectionSize = function () {
        // Reset
        $app.emailAttachmentSize = 0;

        // Find all attachment
        $('.attachment-calc-size').each(function () {
            if ($(this).is(':checked')) {
                // var $size = $( '#' + $(this).attr('id') + '_size' ).val();
                var $size = $($(this)[0].parentNode).find('input[type=hidden]').val();
                $app.emailAttachmentSize += parseFloat($size);
            }
        });

        // Update attachment p tag
        if ($('.attachment-file-size-span').length) {
            // Remove classes
            $('.attachment-file-size-span').removeClass('text-success').removeClass('text-danger').addClass('hidden');
            // If we excceed the limit add text-danger class otherwise add text-success class
            if ($app.emailAttachmentSize) {
                if ($app.emailAttachmentSize >= $landmark.settings.attachmentMaxSize) {
                    $('.attachment-file-size-span').addClass('text-danger');
                    // Add alert
                    swal('Attachments Size Error', 'Sorry, You have selected too many attachments that exceed the maximum size allowed (' + ($landmark.settings.attachmentMaxSize / 1024) + ' MB). Please un-check attachments to make sure they meet the requirement.', 'error');

                } else {
                    $('.attachment-file-size-span').addClass('text-success');
                }

                // Set the value
                var $totalSize = ($app.emailAttachmentSize / 1024);
                $('.attachment-file-size-span').find('i').html($totalSize.toFixed(2));
                $('.attachment-file-size-span').removeClass('hidden');
            }
        }

        return true;
    };

    if ($('.attachment-calc-size').length) {
        $('.attachment-calc-size').on('click', function () {
            $app.attachmentSelectionSize();
        });
        $app.attachmentSelectionSize();
    }
    // Toggle checklist visibility
    $(document).on('click', '.checklist-visible', function () {
        var $value = $(this).val();
        var $checked = $(this).is(':checked') ? 1 : 0;
        // If we select anything other than all, and all is selected then unselect
        if ($value !== 'all' && $('#checklist-visible-all').is(':checked')) {
            $('#checklist-visible-all').prop('checked', false);
        }
        // If we select all and others are checked uncheck them
        else if ($value === 'all') {
            $('.checklist-visible').prop('checked', false);
            $('#checklist-visible-all').prop('checked', true);
        }

        if (!$('.checklist-visible:checked').length) {
            $('#checklist-visible-all').prop('checked', true);
        }

        toggleSelectedVisibilityAndCount();
    });

    // Toggle classes for answered checklist questions
    $(document).on('click', '.checklist-rule-radio', function () {
        var $id = $(this).data('id');
        var $value = $(this).val();
        var $current = $('#tr-rule-' + $id).attr('data-selection');

        // Update the tr class to match the selected value
        $('#tr-rule-' + $id).attr('data-selection', $value);

        // Remove current class
        $('#tr-rule-' + $id).removeClass('tr-rule-checked-' + $current);

        // Add class
        $('#tr-rule-' + $id).addClass('tr-rule-checked-' + $value);

        // Do we have any sub items
        if ($value == 'Y') {
            $('.data-parent-' + $id).removeClass('hidden');
        } else {
            $('.data-parent-' + $id).addClass('hidden');
        }

        toggleSelectedVisibilityAndCount();
    });

    // Toggle classes for answered checklist questions
    $(document).on('click', '.custom-checklist-rule-radio', function () {
        var $id = $(this).data('id');
        var $value = $(this).val();
        var $current = $('#tr-custom-rule-' + $id).attr('data-selection');

        // Update the tr class to match the selected value
        $('#tr-custom-rule-' + $id).attr('data-selection', $value);

        // Remove current class
        $('#tr-custom-rule-' + $id).removeClass('tr-custom-rule-checked-' + $current);

        // Add class
        $('#tr-custom-rule-' + $id).addClass('tr-custom-rule-checked-' + $value);

        toggleSelectedVisibilityAndCount();
    });

    // Format seconds to human
    $.each($('.format-seconds-to-human'), function () {
        $(this).html(moment.utc(($(this).data('seconds') * 1000)).format("HH:mm:ss"));
    });

    // Update email contents
    $('.update-email-contents').click(function () {
        // Mark that we clicked it
        $updateEmailClicked = true;

        buildCorrectionList();
    });

    // Send back button was clicked
    $(document).on('click', '.form-checklist-option-send-back', function () {
        if (!$updateEmailClicked) {
            alert("Sorry, You must click the 'Update Email Contents' button before being able to Send Back the conditions.");
            return false;
        }

        return true;
    });
});

function countQuestionsByAnswer() {
    // Count Yes
    $count['yes'] = $('.tr-rule-checked-Y').length;
    // Count No
    $count['no'] = $('.tr-rule-checked-N').length;
    // Count NA
    $count['na'] = $('.tr-rule-checked--').length;
    // Custom correction unchecked or marked as yes
    $count['custom_yes'] = $('.tr-custom-rule-checked-Y').length;
    $count['custom_no'] = $('.tr-custom-rule-checked-N').length;
    $count['custom_na'] = $('.tr-custom-rule-checked--').length;

    // Update counts
    $('.count-yes').html($count['yes']);
    $('.count-no').html($count['no']);
    $('.count-na').html($count['na']);
}

function buildCorrectionList() {
    var $emailCorrections = [];
    var $emailCorrectionsHTML = '';

    var $count = 0;

    $.each($checklist, function (i, item) {
        var $section = ($count > 0 ? '<br /><br />' : '') + 'SECTION: <strong>' + i + "</strong><br />===============================================<br />";
        var $sectionContents = [];
        $.each(item, function (j, question) {
            if (question.actionReq == 'Y') {
                var $r = $.trim(j + '. ' + question.description);
                var $showComments = false;
                var $actionOrComments = '';

                /*if(question.stips.length) {
                  $showComments = true;
                  $actionOrComments += '<div style="margin-left:10px;">' + question.stips.join('<br />') + '</div>';
                }*/

                if (Object.keys(question.comments).length) {
                    var $comments = [];
                    var $lastItem = question.comments[Object.keys(question.comments)[0]];
                    if ($lastItem) {
                        $comments.push($lastItem.comment);
                    }
                    /*$.each(question.comments, function(c, comment) {
                      //console.log(c, comment);
                      if(comment.comment && comment.comment.length) {
                        $comments.push(comment.comment);
                      }
                    });*/

                    if ($comments.length) {
                        $showComments = true;
                        $actionOrComments += '<div style="margin-left:10px;">' + $comments.join('<br />') + '</div>';
                    }
                }

                if ($showComments) {
                    $r += '<br />&nbsp;&nbsp;<strong>Comments:</strong>' + $actionOrComments;
                }

                $sectionContents.push($r);

                $count++;
            }
        });

        if ($sectionContents.length) {
            $emailCorrections.push($section + $sectionContents.join("<br /><br />"));
        }

    });

    // Add custom checklist comments
    if (Object.keys($comments).length) {
        $emailCorrections.push('<br /><br /><br /><strong>Other Comments</strong><br /><ol>');
        $.each($comments, function (i, item) {
            if (item.comment.length) {
                $emailCorrections.push('<li>' + item.comment + '</li>');
            }
        });
        $emailCorrections.push('</ol><br />');
    }


    // Add custom corrections

    var $dataHTML = $appraiserTemplate;

    // Put them in the email
    if ($emailCorrections.length) {
        $emailCorrectionsHTML += "<br />";
        $.each($emailCorrections, function (i, text) {
            $emailCorrectionsHTML += text;
        });
        $emailCorrectionsHTML += "<br />";
    }

    var $disclaimer = '';

    if ($('#realview-html').contents().find('.blacksmall').length) {
        $disclaimer = '<br /><div style="font-size:9px;"><p>' + $('#realview-html').contents().find('.blacksmall').html() + '</p></div>';
    }

    $dataHTML = $dataHTML.replace('{corrections}', $emailCorrectionsHTML) + $disclaimer;

    $dataHTML = $dataHTML.replace(/<p>&nbsp;<\/p>/g);

    CKEDITOR.instances.appraiser_email_content.setData($dataHTML);

    $updateEmailClicked = true;

    return $emailCorrections;
}


function updateTimers() {
    // Increase total timer count and current timer count every second
    setInterval(function () {
        $('#total_timer_field').val(parseInt($totalTimer++));
        $('#current_timer_field').val(parseInt($currentTimer++));

        $currentTimerMoment += 1000;
        $totalTimerMoment += 1000;

        // Update the current timer to human readable
        $('#current-timer').html(moment.utc($currentTimerMoment).format("HH:mm:ss"));
        $('#total-timer').html(moment.utc($totalTimerMoment).format("HH:mm:ss"));
    }, 1000);
}

function isCleared() {
    return ($count['yes'] > 0 || $count['na'] > 0 || $count['custom_yes'] > 0 || $count['custom_na'] > 0) ? false : true;
}

function toggleFormSendOptions() {
    // Hide both
    $('.form-option-checklist').addClass('hidden');

    if (isCleared()) {
        $('.form-checklist-option-mark-approve').removeClass('hidden');
    } else {
        $('.form-checklist-option-send-back').removeClass('hidden');
    }
}

function toggleSelectedVisibilityAndCount() {
    // Count
    countQuestionsByAnswer();

    // Figure out which one are checked to be checklist-visible
    $checked = $('.checklist-visible:checked');
    $show = [];
    $.each($checked, function () {
        var $value = $(this).val();
        // If it's all then show all
        if ($value == 'all') {
            $('.tr-rule').removeClass('hidden').addClass('shown');

        } else {
            $show.push($value);
        }
    });

    if ($show.length) {
        // Hide all
        $('.tr-rule').removeClass('shown').addClass('hidden');
        // Show required
        $.each($show, function ($i, $val) {
            switch ($val) {
                case 'yes':
                    $('.tr-rule-checked-Y').removeClass('hidden').addClass('shown');
                    break;
                case 'no':
                    $('.tr-rule-checked-N').removeClass('hidden').addClass('shown');
                    break;
                case 'na':
                    $('.tr-rule-checked--').removeClass('hidden').addClass('shown');
                    break;
            }
        });
    }

    // Check for each category make sure it has at least one visible tr
    $.each($('.checklist-section-row'), function () {
        var $visible = $(this).find('.tr-rule.shown').length;
        if ($visible) {
            $(this).find('.count-section').html($visible);
            $(this).show();
        } else {
            $(this).hide();
        }
    });

    // Toggle send options
    toggleFormSendOptions();

    // Mark update email false
    $updateEmailClicked = false;

}