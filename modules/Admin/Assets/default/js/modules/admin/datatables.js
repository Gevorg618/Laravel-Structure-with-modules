(function($) {
  $app.datatables = function($element, $route, $options) {
    if (!$($element).length) {
      return false;
    }

    var $defaults = {
      autoWidth : false,
      serverSide : true,
      iDisplayLength : 50,
      lengthMenu: [ 10, 25, 50, 75, 100, 200, 300, 500 ],
      ajax : $route,
      order : [ [ 0, 'desc' ] ],
      responsive : true,
      columnDefs : [{
        responsivePriority : 1,
        targets : 1
      },
      {
        responsivePriority : 2,
        targets : -1
      }]
    };
    var $dataTable = $($element).DataTable(jQuery.extend($defaults, $options));

    $dataTable.on(
        'init.dt',
        function() {
          $('.dataTables_paginate').find('span.ellipsis').remove();
          $('.dataTables_filter, .dataTables_length').find('input, select')
              .addClass('form-control');
        }).on('preXhr.dt', function() {
      $app.displayLoading($element);
    }).on('xhr.dt', function() {
      $app.hideLoading($element);
    });
    return $dataTable;
  }
})(jQuery);