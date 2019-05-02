$().ready(function() {

    $('.select-all-actions').on('click', function() {
        var $parent = $(this).closest('.panel-default');
        var $value = $(this).is(':checked') ? true : false;
        $parent.find('.action-checkbox').prop('checked', $value);
    });

    $('.select-all-fields-view').on('click', function() {
        var $parent = $(this).closest('.panel-default');
        var $value = $(this).is(':checked') ? true : false;
        $parent.find('.field-view-checkbox').prop('checked', $value);
    });

    $('.select-all-fields-update').on('click', function() {
        var $parent = $(this).closest('.panel-default');
        var $value = $(this).is(':checked') ? true : false;
        $parent.find('.field-update-checkbox').prop('checked', $value);
    });

    $( 'textarea.editor' ).ckeditor({
        width: '100%',
        height: '400px',
        toolbar: 'Full',
        filebrowserBrowseUrl : '/ckfinder/ckfinder.html',
        filebrowserImageBrowseUrl : '/ckfinder/ckfinder.html?Type=Images',
        filebrowserFlashBrowseUrl : '/ckfinder/ckfinder.html?Type=Flash',
        filebrowserUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
        filebrowserImageUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
        filebrowserFlashUploadUrl : '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
    });
    // Enable Mentions
    CKEDITOR.config.enableMentions = true;
    CKEDITOR.config.baseUrl = '/admin/ticketsmanager.php?';
    CKEDITOR.config.mentionsUrl = 'action=find-mentions';
});
