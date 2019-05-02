$(function () {
  var body = $('body');

  // Register event
  $('.filter-change').on('change', function () {
    $ticketsTable.api().ajax.reload();
  });

  // Register select all checkbox
  $('#select_all').on('click', function () {
    $(this).closest('table').find(':checkbox').prop('checked', this.checked);
  });

  // Register refresh button
  $('#refresh-tickets').on('click', function (e) {
    e.preventDefault();
    $ticketsTable.api().ajax.reload();
  });

  // Unlock Ticket
  body.delegate('.option-unlock', 'click', function () {
    var data = {
      id: $(this).attr('data-id'),
      _token: _token
    };

    $.post('/admin/ticket/manager/unlock_ticket', data, function (response) {
      $ticketsTable.api().ajax.reload();
    });
  });

  // Close ticket
  body.delegate('.option-close', 'click', function () {
    var data = {
      'start': $startDate,
      '_token': _token
    };

    $.post('/admin/ticket/manager/close_ticket/' + $(this).attr('data-id'), data, function (response) {
      $ticketsTable.api().ajax.reload();
    });
  });

  // Open ticket
  body.delegate('.option-open', 'click', function () {
    var data = {
      '_token': _token
    };

    $.post('/admin/ticket/manager/open_ticket/' + $(this).attr('data-id'), data, function (response) {
      $ticketsTable.api().ajax.reload();
    });
  });

  // Multi Mod Index Selection
  $('#multimod').on('change', function () {
    var id = $(this).val();

    if (!id) {
      return false;
    }

    // Make sure we have selected at least one ticket
    var checked = getAllCheckedTickets();

    if (!checked.length) {
      alert('Sorry, You must select at least one ticket.');
      return false;
    }

    // Reset selection
    $(this).val('');
    $('#multimod').multiselect('rebuild');

    var data = {
      'checked': checked
    };

    // Run ajax to load the modal window with some content
    $.get('/admin/ticket/manager/get_multi_mod_index_form/' + id, data, function (response) {

      // Show Modal Window
      $('#multi_moderate_model_title').html(response.title);
      $('#multi_moderate_model_content').html(response.html);
      $('#multi_moderate_model').modal();
    });
  });

  // Submit multi mod
  $('#submit-multi-moderation-button').on('click', function () {
    var id = $('#multi_moderation_id').val();
    // Make sure we have selected at least one ticket
    var checked = getAllCheckedTickets();

    if (!checked.length) {
      alert('Sorry, You must select at least one ticket.');
      return false;
    }

    var data = {
      checked: checked,
      _token: _token
    };

    // Process Records
    $.post('/admin/ticket/manager/apply_multi_mod_index/' + id, data, function (response) {
      // Close Modal
      $('#multi_moderate_model').modal('hide');

      // Reload table
      $ticketsTable.api().ajax.reload();
    });
  });

  // Multi Moderate tickets
  $('#multi-moderate-tickets').on('click', function () {
    // Make sure we have selected at least one ticket
    var checked = getAllCheckedTickets();

    if (!checked.length) {
      alert('Sorry, You must select at least one ticket.');
      return false;
    }

    $.get('/admin/ticket/manager/get_multi_moderation_form', {'checked': checked}, function (response) {
      var form = $('#moderate_model');

      $('#moderate_model_title').html(response.title);
      $('#moderate_model_content').html(response.html);
      form.modal();

      registerOrderIdAutoComplete();
      registerCKeditorInstance();

      createBootstrapSelect(form);
    });
  });

  // Submit multi mod
  $('#submit-multi-moderate-button').on('click', function () {
    var data = $('#multi-ticket-mod-form').serialize();

    $.post('/admin/ticket/manager/process_multi_moderation_form', data, function (response) {
      // Close Modal
      $('#moderate_model').modal('hide');

      // Reload table
      $ticketsTable.api().ajax.reload();
    });
  });

  setTimeout(function () {
    loadActivityTab();
  }, 500);

  setTimeout(function () {
    loadStatsTab();
  }, 550);
});

/**
 * Return all checked checkboxes for ticket ids
 *
 */
function getAllCheckedTickets() {
  var ids = [];
  $('input.ticket-ids:checkbox:checked').each(function () {
    ids.push($(this).val());
  });

  return ids;
}

function loadActivityTab() {
  $.get('/admin/ticket/manager/get_activity_tab', function (response) {
    $('#activity').html(response);
  });
}

function loadStatsTab() {
  $.get('/admin/ticket/manager/get_stats_tab', function (response) {
    $('#stats').html(response);
  });
}

/**
 * Register events
 *
 */
function registerInlineEdits() {
  // Category
  $('.inline-category-edit').editable({
    type: 'select',
    url: '/admin/ticket/manager/inline_category_edit',
    source: $categoryList,
    params: {_token: _token},
    ajaxOptions: {
      dataType: 'json'
    },
    display: function (value, response) {
      return false;
    },
    success: function (response, newValue) {
      $(this).html(response);
      $('#refresh-tickets').trigger('click');
    }
  });

  // Status
  $('.inline-status-edit').editable({
    type: 'select',
    url: '/admin/ticket/manager/inline_status_edit',
    source: $statusList,
    params: {_token: _token},
    ajaxOptions: {
      dataType: 'json'
    },
    display: function (value, response) {
      return false;
    },
    success: function (response, newValue) {
      $(this).html(response);
      $('#refresh-tickets').trigger('click');
    }
  });

  // Assigned
  $('.inline-assign-edit').editable({
    type: 'select',
    url: '/admin/ticket/manager/inline_assign_edit',
    source: $assignList,
    params: {_token: _token},
    ajaxOptions: {
      dataType: 'json'
    },
    display: function (value, response) {
      return false;
    },
    success: function (response, newValue) {
      $(this).html(response);
      $('#refresh-tickets').trigger('click');
    }
  });
}