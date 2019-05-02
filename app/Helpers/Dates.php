<?php

function dateDiffHours($from, $to)
{
    $diff = getTotalNumberOfDays(strtotime($from), strtotime($to));
    return sprintf('0 Mo %s D %s H %s M', $diff['d'], $diff['h'], $diff['m']);
}

function getTotalNumberOfDays($from, $to)
{
    $diff = abs( $from - $to  );
    return [
        'd' => intval( $diff / 86400 ),
        'h' => intval( ( $diff % 86400 ) / 3600),
        'm' => intval( ( $diff / 60 ) % 60 ),
        's' => intval( $diff % 60 )
    ];
}

function getOrderTurnTimeInMinutesByTurnTimeString($time)
{
    if (!$time) {
        return 0;
    }

    preg_match('/(-?\d+) Mo (-?\d+) D (-?\d+) H (-?\d+) M/', $time, $matches);

    if (!count($matches) == 5) {
        return 0;
    }


    $months = $matches[1];
    $days = $matches[2];
    $hours = $matches[3];
    $minutes = $matches[4];

    $total = $days;

    if ($months) {
        $total += ($months * 31);
    }

    if ($hours) {
        $total += ($hours / 24);
    }

    if ($minutes) {
        $total += ($minutes / 24 / 60);
    }

    return number_format($total, 3);
}

function getOrderTurnTimeByDates($from, $to)
{
    return dateDiffHours($from, $to);
}