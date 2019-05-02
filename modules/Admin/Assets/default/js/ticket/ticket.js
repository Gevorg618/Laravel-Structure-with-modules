$(function () {

  // Update email template
  $('#template').change(function () {
    var id = $(this).val();
    if (!id) {
      return false;
    }

    $(this).val('');

    // Save current content to the variable for later
    $savedContent = CKEDITOR.instances.reply_text.getData();

    var data = {
      'ticketId': (typeof $ticketId !== 'undefined') ? $ticketId : 0,
      'templateId': id
    };

    $.get('/admin/ticket/manager/get_email_template', data, function (response) {
      var editorInstance = CKEDITOR.instances['reply_text'];

      // Set Content
      var template = $emailTemplate;
      template = template.replace('{html}', response);

      editorInstance.setData(template);
    });
  });

  $.ui.autocomplete.prototype._renderItem = function (ul, item) {
    var term = $.ui.autocomplete.escapeRegex(this.term);

    item.label = item.label.replace(
        new RegExp('(?![^&;]+;)(?!<[^<>]*)(' + term + ')(?![^<>]*>)(?![^&;]+;)', 'gi'), '<strong>$1</strong>'
    );

    return $('<li></li>')
        .data('item.autocomplete', item)
        .append('<a>' + item.label + '</a>')
        .appendTo(ul);
  };
});

/**
 * Basic functions
 */

/**
 * Order ID auto complete
 */
function registerOrderIdAutoComplete() {
  $('.order-search').autocomplete({
    source: function (request, response) {
      $.getJSON('/admin/ticket/manager/search_order', {term: request.term}, response);
    }, select: function (event, ui) {
    }, minLength: 3
  });
}

/**
 * Register CKeditor instance
 */
function registerCKeditorInstance() {
  $('textarea.editor').ckeditor({
    width: '100%',
    height: '200px',
    toolbar: 'Full',
    filebrowserBrowseUrl: '/ckfinder/ckfinder.html',
    filebrowserImageBrowseUrl: '/ckfinder/ckfinder.html?Type=Images',
    filebrowserFlashBrowseUrl: '/ckfinder/ckfinder.html?Type=Flash',
    filebrowserUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
    filebrowserImageUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
    filebrowserFlashUploadUrl: '/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
  });
  // Enable Mentions
  CKEDITOR.config.enableMentions = true;
  CKEDITOR.config.baseUrl = '/admin/ticket/manager/';
  CKEDITOR.config.mentionsUrl = 'find_mentions';
}