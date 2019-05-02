<?php

namespace Modules\Admin\Helpers;


class DateHelper
{
    public static function getTicketDaysArray($from, $to)
    {
        $days = [];

        // Count the number of days between the two days
        $numberOfDays = ceil(abs($to - $from) / 86400);

        // Loop each day and create a date from and date to object for
        // business hours
        for ($i = 0; $i <= $numberOfDays; $i++) {
            $_from = strtotime(sprintf("+%s day", $i), $from);
            if ($_from > $to) {
                break;
            }
            $days[] = [
                'from' => $_from,
                'fromhuman' => date('Y-m-d 06:00:00', $_from),
                'tohuman' => date('Y-m-d 18:00:00', $_from)
            ];
        }

        return $days;
    }

    /**
     * @param $from
     * @param $to
     * @return bool|\DateInterval
     */
    public static function dateDiffObj($from, $to) {
        $datetime1 = new \DateTime($from);
        $datetime2 = new \DateTime($to);
        return $datetime1->diff($datetime2);
    }
}