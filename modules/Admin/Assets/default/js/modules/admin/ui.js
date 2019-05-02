(function($) {
  $app.toggleLoading = function($element) {
    if ($($element).hasClass('has-overlay')) {
      $app.hideLoading($element);
    } else {
      $app.displayLoading($element);
    }
  };

  $app.displayLoading = function($element) {
    if (!$($element).hasClass('has-overlay')) {
      // Show
      var $newElement = $('<div/>', {
        'class': 'loading-overlay'
      }).appendTo($($element));
      // Add loading div for image spinner
      $('<div/>', {
        'class': 'loading-overlay-image'
      }).html('<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i><span class="sr-only">Loading...</span>').appendTo($newElement);
      $($element).addClass('has-overlay');
    }
  };

  $app.hideLoading = function($element) {
    if ($($element).hasClass('has-overlay')) {
      // Hide
      $('.loading-overlay', $($element)).remove();
      $($element).removeClass('has-overlay');
    }
  };
})(jQuery);