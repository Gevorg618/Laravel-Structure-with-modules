$app.updateTimeSinceCreated = function () {
  $.each($('.time-show-since'), function () {
    var $time = $(this).attr('data-time');
    var $_moment = moment.tz($time, $landmark.settings.timezone);
    var $now = moment().tz($landmark.settings.timezone);
    var $seconds = $now.diff($_moment, 'minutes');
    var $minutes = $now.diff($_moment, 'minutes');
    var $hours = $now.diff($_moment, 'hours');
    var $days = $now.diff($_moment, 'days');
    var $dur = moment.duration(($now.unix() - $_moment.unix()), 'seconds');
    var $duration = moment.duration($dur.asSeconds() - 1, 'seconds');
    var $text = '';
    if ($duration.years() > 0) {
      $text += $duration.years() + 'y:';
    }
    if ($duration.months() > 0) {
      $text += $duration.months() + 'm:';
    }
    if ($duration.days() > 0) {
      $text += $duration.days() + 'd:';
    }
    if ($duration.hours() > 0) {
      $text += $duration.hours() + 'h:';
    }
    if ($duration.minutes() > 0) {
      $text += $duration.minutes() + 'm:';
    }
    if ($duration.seconds() > 0) {
      $text += $duration.seconds() + 's';
    }
    $(this).removeClass('label-default').removeClass('label-info').removeClass('label-warning').removeClass('label-danger');
    if ($hours >= 3) {
      $(this).addClass('label-danger');
    } else if ($hours >= 2) {
      $(this).addClass('label-warning');
    } else if ($minutes > 30) {
      $(this).addClass('label-info');
    } else {
      $(this).addClass('label-default');
    }
    if ($_moment.isValid()) {
      $(this).html($text);
    } else {
      $(this).html('');
    }
  });
}