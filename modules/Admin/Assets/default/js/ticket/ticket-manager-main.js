var _token = $('input[name="_token"]').val();

$(function () {
  var tab = $('#myTab');

  tab.find('a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
  });

  // store the currently selected tab in the hash value
  $('ul.nav-tabs > li > a').on('shown.bs.tab', function (e) {
    //var id = $(e.target).attr("href").substr(1);
    //window.location.hash = id;
  });

  // on load of the page: switch to the currently selected tab
  var hash = window.location.hash;
  if (!hash) {
    hash = '#tickets';
  }

  tab.find('a[href="' + hash + '"]').tab('show');

  // Order Search
  registerOrderIdAutoComplete();

  setInterval('$app.updateTimeSinceCreated();', 1000);
});

/**
 * Additional Conditions
 */
function getFilterConditions() {
  return {
    'grouped': $('#grouped').val(),
    'open_or_close': $('#open_or_close').val(),
    'status': $('#status').val(),
    'category': $('#category').val(),
    'priority': $('#priority').val(),
    'timezone': $('#timezone').val()
  };
}