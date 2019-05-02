<?php

use Carbon\Carbon;

function checkCarbonDateValid($date, $format = 'Y-m-d H:i:s')
{
    if (!isValidDate($date)) {
        return null;
    }

    try {
        $date = \Carbon\Carbon::createFromFormat($format, $date);
    } catch (\Exception $e) {
        $date = null;
    }

    return $date;
}

function carbon($date, $format = 'Y-m-d H:i:s')
{
    return \Carbon\Carbon::createFromFormat($format, $date);
}

function isValidDate($date)
{
    return trim($date) && !in_array($date, ['0000-00-00 00:00:00', '0000-00-00', '00:00:00', '-0001-11-30 00:00:00']);
}

function timeAgo($date, $format = 'Y-m-d H:i:s')
{
    return \Carbon\Carbon::createFromFormat($format, $date)->diffForHumans();
}

function formatDate($date, $format='m/d/Y h:i A', $source = 'Y-m-d H:i:s')
{
    return \Carbon\Carbon::createFromFormat($source, $date)->format($format);
}

function numbers(int $min = 1, int $max = 10)
{
    for ($i = 1; $i <= $max; $i++) {
        yield $i => $i;
    }
}

function days()
{
    for ($i = 1; $i <= 31; $i++) {
        yield $i => $i;
    }
}

function months()
{
    for ($i = 1; $i <= 12; $i++) {
        $date = Carbon::createFromFormat('Y-m-d', sprintf('%s-%s-01', date('Y'), $i));
        yield $date->format('m') => $date->format('\(m\) F');
    }
}

function years($year = null, $limit = 10)
{
    $year = $year !== null ? $year : date('Y');
    for ($i = $year; $i <= ($year + $limit); $i++) {
        $date = Carbon::createFromFormat('Y-m-d', sprintf('%s-01-01', $i));
        yield $date->format('Y') => $date->format('Y');
    }
}

function monthFromNumber($month) {
    $date = Carbon::createFromFormat('!m', $month);
    return $date->format('F');
}

function tzConvert($date, $tz='America/Los_Angeles') {
    $date = new Carbon($date);
    $date->setTimezone($tz);

    return $date;
}