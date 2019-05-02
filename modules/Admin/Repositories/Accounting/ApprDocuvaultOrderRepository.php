<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\DocuVault\Order;
use App\Models\Customizations\Status;
use Illuminate\Support\Collection;

class ApprDocuvaultOrderRepository
{
    const DOCUVAULT = 'docuvault';

    /**
     * @param $dateFrom
     * @param $dateTo
     * @param $clients
     * @return Collection
     */
    public function getOrdersForBatch($dateFrom, $dateTo, $clients)
    {
        $orders = Order::with([
            'user',
            'notification'
        ])->where('status', '<>', Order::STATUS_TEMP)
            ->whereHas('notification', function ($query) use ($dateFrom, $dateTo){
                return $query->whereBetween('created_date', [
                    $dateFrom,
                    $dateTo
                ]);
            })->where(function ($query) {
                return $query->where('final_appraisal_borrower_sendtoemail', 'Y')
                    ->orWhere('final_appraisal_borrower_sendtopostalmail', 'Y');
            });
        if ($clients) {
            $orders = $orders->whereHas('user', function ($query) use ($clients) {
                return $query->whereHas('group', function ($q) use ($clients) {
                    return $q->whereIn('user_groups.id', $clients);
                });
            });
        }
        return $orders->get()->sortBy(function (DocuVaultOrder $item, $key) {
            return $item->notification->created_date;
        });
    }

    public function getOrderById($id)
    {
        return Order::find($id);
    }
}