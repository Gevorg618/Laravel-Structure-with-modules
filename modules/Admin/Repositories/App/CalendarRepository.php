<?php

namespace Modules\Admin\Repositories\App;


use App\Models\Appraisal\Order;
use Modules\Admin\Helpers\StringHelper;

/**
 * Class CalendarRepository
 * @package Modules\Admin\Repositories
 */
class CalendarRepository
{
    const FIFTEEN_DAYS = 1;
    const THIRTY_DAYS = 2;
    const FORTY_FIVE_DAYS = 3;
    const SIXTY_DAYS = 4;

    /**
     * @param $start
     * @param $end
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getData($start, $end)
    {
        return Order::select(\DB::raw("
            date_delivered, 
            DATE_FORMAT(date_delivered, '%Y-%m-%d') as fdate,
            DATEDIFF(NOW(), date_delivered) as diff,
            SUM(case when DATEDIFF(NOW(), date_delivered) <= 15 then split_amount else 0 end) as d1,
            SUM(case when DATEDIFF(NOW(), date_delivered) > 15 and DATEDIFF(NOW(), date_delivered) <= 30 then split_amount else 0 end) as d2,
            SUM(case when DATEDIFF(NOW(), date_delivered) > 30 and DATEDIFF(NOW(), date_delivered) <= 45 then split_amount else 0 end) as d3,
            SUM(case when DATEDIFF(NOW(), date_delivered) > 45 then split_amount else 0 end) as d4,
            SUM(case when DATEDIFF(NOW(), date_delivered) <= 15 then split_amount else 0 end) as total1,
            SUM(case when DATEDIFF(NOW(), date_delivered) > 15 and DATEDIFF(NOW(), date_delivered) <= 30 then split_amount else 0 end) as total2,
            SUM(case when DATEDIFF(NOW(), date_delivered) > 30 and DATEDIFF(NOW(), date_delivered) <= 45 then split_amount else 0 end) as total3,
            SUM(case when DATEDIFF(NOW(), date_delivered) > 45 then split_amount else 0 end) as  total4
        "))->whereBetween('date_delivered', [$start, $end])
            ->groupBy('fdate')->orderBy('date_delivered')->get();
    }

    /**
     * @param $range
     * @param $amount
     * @return string
     */
    public function getTitleByRange($range, $amount) {
        $title = '';
        switch($range) {
            case self::FIFTEEN_DAYS:
                $title = '15 Days';
                break;
            case self::THIRTY_DAYS:
                $title =  '30 Days';
                break;
            case self::FORTY_FIVE_DAYS:
                $title =  '45 Days';
                break;
            case self::SIXTY_DAYS:
                $title =  '60+ Days';
                break;
        }

        return $title . ' - ' . StringHelper::formatValue($amount, 'currency');
    }

    /**
     * @param $range
     * @return string
     */
    public function getColorByRange($range) {
        switch($range) {
            case self::FIFTEEN_DAYS:
                return '#5cb85c';
            case self::THIRTY_DAYS:
                return '#5bc0de';
            case self::FORTY_FIVE_DAYS:
                return '#f0ad4e';
            case self::SIXTY_DAYS:
                return '#d9534f';
        }
    }
}