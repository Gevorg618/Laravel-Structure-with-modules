$(function () {
  var body = $('body');

  registerOrderIdAutoComplete();
  registerCKeditorInstance();

  // Add Condition Row
  $('#add-condition').on('click', function () {
    // Init
    $totalConditions++;

    // Prepare template
    var template = $conditionRowTemplate;
    // Replace values
    template = template.replace(/{id}/g, $totalConditions)
        .replace(/{key}/g, '')
        .replace(/{type}/g, '')
        .replace(/{value}/g, '');

    // Add New row under the conditions
    $('#rule_conditions').append(template);
  });

  // Remove Condition
  body.delegate('.remove-condition-row', 'click', function () {
    $(this).closest('.condition-row').remove();
  });

  // Condition Key Selected
  body.delegate('.condition_key', 'change', function () {
    var row = $(this).closest('.condition-row');
    var value = $(this).val();
    var conditions = JSON.parse($conditionKeyMatched);

    // Hide all by default
    row.find('.condition-match-type-div, .condition-value-div, .condition-category-div').addClass('hidden');

    // Check if we need to show the match type
    if (typeof conditions[value] !== 'undefined') {
      // If this is a category selected show the dropdown instead of the value
      if (value === 'category') {
        row.find('.condition-category-div').removeClass('hidden');
      } else {
        row.find('.condition-match-type-div, .condition-value-div').removeClass('hidden');
      }
    }
  });

  // Run the condition_key event to show/hide all our
  // required data
  $.each($('.condition-row'), function () {
    $(this).find('.condition_key').trigger('change');
  });

});