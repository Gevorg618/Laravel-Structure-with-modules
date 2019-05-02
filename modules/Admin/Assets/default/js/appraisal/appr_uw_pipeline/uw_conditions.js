$(document).ready(function () {
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

    // Remove all conditions
    $(document).on('click', '#remove-all-conditions', function () {
        var $confirm = confirm("Are you sure you want to remove all conditions?\nRemoving all conditions will reset the status to Appraisal Completed.");
        if (!$confirm) {
            return false;
        }
        let data = $('#uw_form').serialize();
        $.ajax({
            url: '/admin/post-completion-pipelines/appr-uw-pipeline/remove-all-conditions/' + $orderId,
            method: 'delete',
            data: data
        }).done(res => {
            if (res.success) {
                window.location = '/admin/post-completion-pipelines/appr-uw-pipeline/uw-conditions/' + $orderId
            }
            console.log('response', res)
        }).fail(err => {
            console.log(err)
        })
    });

    // Capture enter event for certain fields to prevent form submission
    $('.client-rows-table input, .condition-rows-table select').keypress(function (e) {
        if (e.which == 13) {
            e.preventDefault();
            return false;
        }
    });

    // Add New Client row
    $(document).on('click', '.add-client', function () {
        $totalClients++;
        addClient($totalClients);
    });

    // Remove client row
    $(document).on('click', '.remove-client', function () {
        $(this).parent().parent().remove();
    });

    // Add New Condition row
    $(document).on('click', '.add-condition', function () {
        $totalConditions++;
        addConditions($totalConditions)
    });

    // Remove condition row
    $(document).on('click', '.remove-condition', function () {
        $(this).parent().parent().remove();
    });

    // Send email to appraiser
    $(document).on('change', '#send_to_appr', function () {
        if ($(this).is(':checked')) {
            var $dataHTML = $editorTemplate;
            var $conditions = "<ol>\n";

            // Get all conditions and create a list
            $.each($('.cond-text'), function (i, item) {
                var $id = $(item).attr('data-id');
                var $category = $('#uw-conditions-' + $id).find('.cond-cat').val();
                if (item.value && $.inArray($category, $ignoredCategories) == -1) {
                    $conditions += "<li>" + item.value + "</li>\n";
                }
            });

            $conditions += "</ol>\n";

            $dataHTML = $dataHTML.replace('%conditions%', $conditions);
            CKEDITOR.instances.email_message.setData($dataHTML);

            $('.form-checklist-option-send-back').removeClass('hidden');

        } else {
            $('.form-checklist-option-send-back').addClass('hidden');
        }
    });

    /**
     * Submit Form
     */
    $(document).on('submit', '#uw_form', function () {
        $hasError = false;

        if ($('#send_to_appr').is(':checked')) {
            console.log($('#email_name').val());
            if (!$('#appraiser_name').val()) {
                alert("Please fill out the Appraiser Email Name");
                $hasError = true;
                return false;
            }

            if (!$('#appraiser_email').val()) {
                alert("Please fill out the Appraiser Email Address");
                $hasError = true;
                return false;
            }

            if (!$('#email_subject').val()) {
                alert("Please fill out the QC Corrections Email Subject");
                $hasError = true;
                return false;
            }
            if (!CKEDITOR.instances.email_message.getData()) {
                alert("Please fill out the Appraiser Email Message");
                $hasError = true;
                return false;
            }
        }

        // Make sure all the categories were selected
        $.each($('.condition_tr').find('.cond-cat'), function () {
            var $val = $(this).val();
            if (!$val) {
                $hasError = true;
                alert('Please select all categories for all conditions added.');
            }
        });

        if ($hasError) {
            return false;
        }

        if ($inEditMode && $('.condition_tr').length <= 0) {
            var $confirm = confirm('It appears that there are no conditions, Are you sure you would like to save?');
            if (!$confirm) {
                return false;
            }
        }

        return true;
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
        $(document).on('click', '.attachment-calc-size', function () {
            $app.attachmentSelectionSize();
        });
        $app.attachmentSelectionSize();
    }

    function addClient(count) {
        let tr = document.createElement('tr');

        let nameTd = document.createElement('td');
        let name = document.createElement('input');
        $(name).attr('name', `contact_name[${count}]`);
        $(name).addClass('form-control');
        $(nameTd).append(name);

        let emailTd = document.createElement('td');
        let email = document.createElement('input');
        $(email).attr('name', `contact_email[${count}]`);
        $(email).addClass('form-control');
        $(emailTd).append(email);

        let buttonTd = document.createElement('td');
        let removeButton = document.createElement('button');
        $(removeButton).addClass('btn btn-xs btn-danger remove-client');
        $(removeButton).attr('type', 'button');
        $(removeButton).text('Remove');
        $(removeButton).attr('id', `contact_add_${count}`);
        $(buttonTd).append(removeButton);

        $(tr).append(nameTd, emailTd, buttonTd);
        $('.client-rows-table').append(tr);
    }

    function addConditions(count) {
        let tr = document.createElement('tr');
        $(tr).addClass('condition_tr');
        $(tr).attr('id', `uw-conditions-${count}`);

        let textTd = document.createElement('td'),
            textArea = document.createElement('textarea');
        $(textArea).addClass('cond-text form-control');
        $(textArea).css({
            'height' : '100px',
            'resize' : 'vertical'
        });
        $(textArea).attr('name', `condition_text[${count}]`);
        $(textArea).attr('id', `condition_text[${count}]`);
        $(textArea).attr('data-id', count);
        $(textTd).append(textArea);

        let categoryTd = document.createElement('td'),
            categorySelect = document.createElement('select');
        $(categorySelect).addClass('cont-cat form-control');
        $(categorySelect).attr('name', `condition_category[${count}]`);
        $(categorySelect).attr('id', `condition_category[${count}]`);
        let disOption = document.createElement('option');
        $(disOption).attr('selected', 'selected');
        $(disOption).attr('value', '');
        $(disOption).text('-- Select --');
        $(categorySelect).append(disOption);
        $.each($UWCategories, (index, value) => {
            let tmp = document.createElement('option');
            $(tmp).attr('value', value['key']);
            $(tmp).text(value['title']);
            $(categorySelect).append(tmp);
        });

        $(categoryTd).append(categorySelect);

        let responseTd = document.createElement('td'),
            responseArea = document.createElement('textarea');
        $(responseArea).addClass('cond-text form-control');
        $(responseArea).css({
            'height' : '100px',
            'resize' : 'vertical'
        });
        $(responseArea).attr('name', `condition_response[${count}]`);
        $(responseArea).attr('id', `condition_response[${count}]`);
        $(responseArea).attr('data-id', count);
        $(responseTd).append(responseArea);

        let nameTd = document.createElement('td');

        let removeTd = document.createElement('td'),
            removeButton = document.createElement('button');
        $(removeButton).addClass('btn btn-xs btn-danger remove-condition');
        $(removeButton).text('Remove');
        $(removeButton).attr('id', count);
        $(removeButton).attr('type', 'button');
        $(removeTd).append(removeButton);

        $(tr).append(textTd, categoryTd, responseTd, nameTd, removeTd);
        $('.condition-rows-table').append(tr);
    }
});
