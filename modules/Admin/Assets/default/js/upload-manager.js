$(function () {
  var body = $('body');

  // Disable button on submitting
  $('#upload').click(function (e) {
    $(this).val('Uploading..').attr('disabled', true);
    $('#upload-form').submit();
  });

  // Copy to clipboard
  body.delegate('.copy-link', 'click', function () {
    var link = $(this).data('link');
    var temp = $('<input>');
    body.append(temp);

    temp.val(link).select();
    document.execCommand('copy');

    temp.remove();
  });

});