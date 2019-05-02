;(function($) {
  var source   = $("#search-result-row").html();
  var template = Handlebars.compile(source);

  jQuery('#top-search').on('blur', function() {
    $(this).val('');
  });

  jQuery('#navbar-search-type').on('change', function() {
    $('#top-search').val('');
  });

  jQuery('#top-search').on('keyup', _.debounce(function() {
    $type = $('#navbar-search-type').val();
    $term = $(this).val();
    
    if(!$term || $term.length < 2) {
      return false;
    }

    $.ajax({
      url: '/admin/search',
      data: {type: $type, 'term': $term},
      dataType: 'json',
      success: function(data) {
        $('.search-bar-results').html('');
        if(data.length) {
          _.forEach(data, function(value, key) {
            $('.search-bar-results').append(template(value));
          });
        } else {
          $('.search-bar-results').html(template({'link': 'javascript:void(0)', 'title': 'No Results Found', 'description': ''}));
        }
        $('.search-bar-results').show();
      },
      error: function(jqXHR, textStatus, errorThrown) {
        if(jqXHR.responseJSON.message) {
          self.toastr('error', jqXHR.responseJSON.message);
          toastr['error'](jqXHR.responseJSON.message, 'Rrror');
        }
      }
    });
  }, 300));

})(jQuery);
