<?php

namespace Modules\Admin\Repositories\Accounting;

use App\Models\Appraisal\DocuVaultOrder;


/**
 * Class DocuvaultOrderRepository
 * @package Modules\Admin\Repositories
 */
class DocuvaultOrderRepository
{
    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getDocuvaultExternalMonthlyReport()
    {
        return DocuVaultOrder::select(\DB::raw("
            DATE_FORMAT(ordereddate, '%Y/%m') as date_ordered, 
            SUM(invoicedue) as total_invoice, 
            SUM(paid_amount) as total_paid
        "))->where('ordereddate', '<>', '0000-00-00')
            ->groupBy('date_ordered')
            ->orderBy('date_ordered')->get();
    }
}