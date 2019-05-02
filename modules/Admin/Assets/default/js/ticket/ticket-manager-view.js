$isActive = true;

$(function () {
  var body = $('body');

  // Toggle attachments box
  $('.toggle-attachments').click(function () {
    var attach = $('#attachments-box')

    if (attach.hasClass('hidden')) {
      attach.removeClass('hidden')
    } else {
      attach.addClass('hidden')
    }
  });

  // Quick Close Ticket
  $('.quick-option-close').on('click', function () {
    var data = {
      'next': $(this).hasClass('and-next') ? 1 : 0,
      'params': $('#params').val(),
      'start': $startDate,
      '_token': _token
    };

    $.post('/admin/ticket/manager/close_ticket/' + $(this).attr('data-id'), data, function (response) {
      if (data.next) {
        window.location.href = response.redirect;
      } else {
        window.location.reload();
      }
    });
  });

  // Quick Open Ticket
  $('.quick-option-open').on('click', function () {
    var data = {
      '_token': _token
    };

    $.post('/admin/ticket/manager/open_ticket/' + $(this).attr('data-id'), data, function (response) {
      window.location.reload();
    });
  });

  $('iframe').load(function () {
    var maxHeight = 500;
    var height = $(this).contents().innerHeight();

    $('.ticket_contents_frame').contents().find('body').append($('<style type="text/css">p {margin: 5px;}</style>'));

    if (height > maxHeight) {
      $('iframe').css({'height': parseInt(maxHeight) + 'px'});

    } else if (height < maxHeight && height > $('iframe').parent().innerHeight()) {
      $('iframe').css({'height': parseInt(height + 80) + 'px'});
    }
  });

  // Listen to reply checkbox
  $('#reply_checkbox').on('click', function () {
    var temp = '';
    var replyAll = $('#reply_all');

    // See if we need to add our template or
    // use default content
    if ($(this).is(':checked')) {
      if (!$savedContent) {
        // Save current content to the variable for later
        $savedContent = CKEDITOR.instances.reply_text.getData();
      }

      // Set Content
      temp = $emailTemplate;
      temp = temp.replace('{html}', $savedContent);

      // Show Reply Options
      $('.reply-hidden').removeClass('hidden');
      // Check the reply all checkbox
      replyAll.attr('checked', true);

    } else {
      // See if we have content saved and restore it
      if ($savedContent) {
        temp = $savedContent;
        $savedContent = null;
      } else {
        // Empty the box
        temp = '';
      }

      // Hide Reply Options
      $('.reply-hidden').addClass('hidden');
      replyAll.attr('checked', false);
    }

    replyAll.trigger('change');

    // Replace Content
    var editorInstance = CKEDITOR.instances['reply_text'];
    editorInstance.setData(temp);
  });

  // Reply all handler
  $('#reply_all').on('change', function () {
    if ($(this).is(':checked')) {
      // Show Reply Options
      $('.reply-all-hidden').removeClass('hidden');
    } else {
      // Hide Reply Options
      $('.reply-all-hidden').addClass('hidden');
    }
  });

  // Register for ticket view
  if ($ticketId) {
    var idleTimeout = 15; // minutes

    // Set idle timeout
    $(document).idleTimer(idleTimeout * 1000 * 60);

    $(document).on('idle.idleTimer', function () {
      // If we are already inactive then stop

      if (!$isActive) {
        return false;
      }
      // Hide current modals
      $.each($('.modal'), function () {
        $(this).modal('hide');
      });

      // function you want to fire when the user goes idle
      $('#order_modal_idle_title').html('Inactive for longer then ' + idleTimeout + ' minute(s)');
      $('#order_modal_idle').modal({backdrop: 'static', keyboard: false});
      $isActive = false;
    });

    // Coming back from idle
    $(document).bind('active.idleTimer', function () {
      // Fire any functions
    });

    // Mark as active again
    $('#order_modal_idle_back').on("click", function () {
      $('#order_modal_idle').modal('hide');
      $isActive = true;
    });

    if ($autoCheckCurrentlyViewing) {
      setInterval("autoCheckViewingUpdate()", $autoCheckCurrentlyViewingInterval);
    }
  }

  // Activity Visibility
  body.delegate('.show-visibility', 'click', function () {
    var comment = $('.media-comment');
    var activity = $('.media-activity');

    if ($(this).hasClass('show-all-activity')) {
      comment.show();
      activity.show();

      var count = $('.media-single-activity').length;
    } else if ($(this).hasClass('show-comments')) {
      comment.show();
      activity.hide();

      count = comment.length;
    } else if ($(this).hasClass('show-activity')) {
      activity.show();
      comment.hide();

      count = comment.length;
    }

    // Set count
    $('.activity_count_title').html('(' + count + ')');
  });

  // View Comment Content
  $('.view-comment-content').on('click', function () {
    var id = $(this).attr('data-id');
    if (!id) {
      return false;
    }

    $.get('/admin/ticket/manager/get_comment_content/' + id, {}, function (response) {
      $('#comment_modal_title').html(response.title);
      $('#comment_modal_content').html(response.html);
      $('#comment_modal').modal();
    });
  });

  // Refresh Comments
  body.delegate('#refresh_comments_button', 'click', function () {
    var container = $('#ticket_comments_container');
    container.html('<i class="fa fa-refresh fa-spin"></i>');

    $.get('/admin/ticket/manager/get_ticket_comments', {'id': $ticketId}, function (response) {
      container.html(response);
    });
  });

  // Update comment visibility
  body.delegate('.set-comment-visibility', 'click', function () {
    var id = $(this).attr('data-id');
    var comment = $('#ticket_comment_item_' + id);

    var data = {
      public: $(this).hasClass('set-comment-public') ? 1 : 0,
      _token: _token
    };

    $.post('/admin/ticket/manager/set_comment_visibility/' + id, data, function (response) {
      comment.html(response);
    });
  });

  // Remove Participant
  $('.remove-participant-user').on('click', function () {
    var id = $(this).attr('data-id');
    var element = $(this);

    var data = {
      id: id,
      ticketId: $ticketId,
      _token: _token
    };

    $.post('/admin/ticket/manager/remove_participant_user', data, function (response) {
      // Remove name
      element.parent().remove();

      // Refresh logs
      $('#refresh_comments_button').trigger('click');
    });
  });

  // Multi Mod Selection
  $('#multi').on('change', function () {
    var id = $(this).val();

    if (!id) {
      return false;
    }

    // Reset selection
    $(this).val('');

    // Run ajax to load the modal window with some content
    $.get('/admin/ticket/manager/get_multi_mod_record/' + id, {}, function (response) {
      // Clear Current selections
      clearCurrentModerationInputs();
      multiModFillInModerationInputs(response);
    });
  });
});

/**
 * Clear current moderation options
 */
function clearCurrentModerationInputs() {
  $('#ticket_reply_options :input').val('');
  $('#ticket_reply_options :checkbox').attr('checked', false);

  // Trigger events
  $('.reply-hidden').addClass('hidden');
  $('.reply-all-hidden').addClass('hidden');

  // Refresh bootstrap multi select
  $.each($('.bootstrap-multiselect'), function () {
    $(this).multiselect('rebuild');
  });
}

/**
 * Fill in the moderation inputs from the multi mod record
 */
function multiModFillInModerationInputs(row) {
  // Open Or Close
  if (row.close_or_open) {
    if (row.close_or_open == 'open') {
      $('#open_ticket').attr('checked', true);
    } else if (row.close_or_open == 'close') {
      $('#close_ticket').attr('checked', true);
    }
  }

  // Public Comment
  if (row.public_comment > 0) {
    $('#public_comment').attr('checked', true);
  }

  // Assign
  if (row.assign_to != '') {
    $('#assign').val(row.assign_to);
  }

  // Status
  if (row.set_status) {
    $('#status').val(row.set_status);
  }

  // Category
  if (row.set_category) {
    $('#category').val(row.set_category);
  }

  // Priority
  if (row.set_priority) {
    $('#priority').val(row.set_priority);
  }

  // Assign Order
  if (row.assign_order) {
    $('#orderid').val(row.assign_order);
  }

  // Participants
  if (row.add_participants) {
    // Select selections
    var $options = row.add_participants.split(',');
    $('#participants').multiselect('select', $options);
  }

  // Reply
  if (row.reply > 0) {
    $('#reply_checkbox').attr('checked', true);
    $('.reply-hidden').removeClass('hidden');
  }

  // Reply All
  if (row.reply_all > 0) {
    $('#reply_all').attr('checked', true);
    $('.reply-all-hidden').removeClass('hidden');
  }

  // Comment
  if (row.comment) {
    var editorInstance = CKEDITOR.instances['reply_text'];
    // Set Content
    editorInstance.setData(row.comment);
  }
}

function autoCheckViewingUpdate() {
  // Make sure we are active
  if (!$isActive) {
    return false;
  }

  var data = {
    'viewing': $currentlyViewing
  };

  $.get('/admin/ticket/manager/get_currently_viewing_update/' + $ticketId, data, function (response) {
    if (response.error && response.error !== '') {
      return false;
    }

    // Update list
    $('#currently_viewing_div').html(response.currently);
    $('#last_viewed_div').html(response.last);

    // Update array
    $currentlyViewing = response.viewing;

    var msg = '';
    if (response.addedNames && response.addedNames.length > 0) {
      if (response.addedNames.length >= 2) {
        msg += response.addedNames.join(', ') + ' are now viewing this ticket.';
      } else {
        msg += response.addedNames.join(', ') + ' is now viewing this ticket.';
      }
    }

    if (response.leftNames && response.leftNames.length > 0) {
      if (msg && msg.length > 0) {
        msg += '<br>';
      }
      msg += response.leftNames.join(', ') + ' Stopped viewing this ticket.';
    }

    if (msg && msg.length > 0) {
      showNotificationMessage('Currently Viewing Update', msg, 15000);
    }
  });
}

/**
 * Show notification message
 *
 */
function showNotificationMessage(title, msg, delaytime) {
  $.pnotify({
    title: title,
    text: msg,
    type: 'info',
    hide: true,
    delay: delaytime,
    animation: 'slide',
  });
}