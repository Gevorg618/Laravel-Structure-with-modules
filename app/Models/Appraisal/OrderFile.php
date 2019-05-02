<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

class OrderFile extends BaseModel
{
    protected $table = 'appr_order_files';

    public $timestamps = false;

    /**
     * @return string
     */
    public function getFilePathAttribute()
    {
        if ($this->file_location) {
            return config('app.order_doc_folder') . '/' . $this->file_location . '/' . $this->filename;
        }

        return config('app.order_doc_folder') . '/' . $this->order_id . '_' . $this->filename;
    }

    public static function getOrderDocumentVaultAllDocuments($id)
    {
        return self::where('order_id', $id)->where('is_client_visible', 1)->where('is_aws', 1)->orderBy('created_at', 'DESC')->get();
    }

    public static function getOrderFinalAppraisalDocument($orderId, $orderRepository)
    {
        // Check if we have aws uploaded final appraisal
        $order = null;
        $finalReport = null;
        $order = $orderRepository->getApprOrderById($orderId);
        if ($order->revision) {
            $finalReport = self::where('order_id', $orderId)->where('is_final_report', 1)->where('revision', $order->revision)->first();
        } else {
            $finalReport = self::where('order_id', $orderId)->where('is_final_report', 1)->first();
        }
        if ($finalReport) {
            return $finalReport;
        }
        return null;
    }
}
