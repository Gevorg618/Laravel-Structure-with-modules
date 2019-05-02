<?php

namespace App\Models\DocuVault;

use Illuminate\Database\Eloquent\Model;

class OrderFiles extends Model
{
    protected $table = 'docuvault_order_files';

    public static function getAllOrderDocumentVaultDocuVaultDocuments($id)
    {
        return self::where('order_id', $id)->orderBy('created_at', 'ASC')->get();
    }

    public static function getDocuVaultOrderFinalAppraisalDocument($orderId)
    {
        // Check if we have aws uploaded final appraisal
        $finalReport = self::where('order_id', $orderId)->where('is_icc', 1)->first();
        if($finalReport) {
            return $finalReport;
        }
        return null;
    }

    public static function getDocuVaultOrderInvoiceDocument($orderId)
    {
        return self::where('order_id', $orderId)->where('is_invoice', 1)->first();
    }

    public static function getDocuVaultOrderICCDocument($orderId)
    {
        return self::where('order_id', $orderId)->where('is_icc', 1)->first();
    }
}
