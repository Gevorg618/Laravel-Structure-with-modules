<?php

namespace Modules\Admin\Repositories\Ticket;

use Modules\Admin\Contracts\Ticket\OrderDocumentContract;
use App\Models\OrderDocuments\Document;

class OrderDocumentRepository implements OrderDocumentContract
{
    private $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * @param \App\Models\Appraisal\Order $order
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOrderDocumentsByLocationCode($order, $code)
    {
        return $this->document->where('is_active', 1)
            ->leftJoin(
                'order_documents_location_relation',
                'order_documents.id', '=', 'order_documents_location_relation.file_id'
            )
            ->leftJoin(
                'order_documents_locations',
                'order_documents_location_relation.location_id', '=', 'order_documents_locations.id'
            )
            ->leftJoin('order_documents_state', 'order_documents.id', '=', 'order_documents_state.file_id')
            ->leftJoin('order_documents_appr_type', 'order_documents.id', '=', 'order_documents_appr_type.file_id')
            ->leftJoin('order_documents_loan_type', 'order_documents.id', '=', 'order_documents_loan_type.file_id')
            ->leftJoin('order_documents_loan_reason', 'order_documents.id', '=', 'order_documents_loan_reason.file_id')
            ->leftJoin('order_documents_prop_type', 'order_documents.id', '=', 'order_documents_prop_type.file_id')
            ->leftJoin('order_documents_occ_status', 'order_documents.id', '=', 'order_documents_occ_status.file_id')
            ->where(function ($query) use ($order) {
                $query->where('order_documents_state.state', '=', $order->propstate)
                    ->orWhereNull('order_documents_state.state');
            })
            ->where(function ($query) use ($order) {
                $query->where('order_documents_appr_type.appr_type_id', '=', $order->appr_type)
                    ->orWhereNull('order_documents_appr_type.appr_type_id');
            })
            ->where(function ($query) use ($order) {
                $query->where('order_documents_loan_type.type_id', '=', $order->loantype)
                    ->orWhereNull('order_documents_loan_type.type_id');
            })
            ->where(function ($query) use ($order) {
                $query->where('order_documents_loan_reason.type_id', '=', $order->loanpurpose)
                    ->orWhereNull('order_documents_loan_reason.type_id');
            })
            ->where(function ($query) use ($order) {
                $query->where('order_documents_prop_type.type_id', '=', $order->prop_type)
                    ->orWhereNull('order_documents_prop_type.type_id');
            })
            ->where(function ($query) use ($order) {
                $query->where('order_documents_occ_status.type_id', '=', $order->occstatus)
                    ->orWhereNull('order_documents_occ_status.type_id');
            })
            ->where('order_documents_locations.code', $code)
            ->get();
    }

    /**
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getDocumentsByLocationCode($code)
    {
        return $this->document->where('is_active', 1)
            ->leftJoin(
                'order_documents_location_relation',
                'order_documents.id', '=', 'order_documents_location_relation.file_id'
            )
            ->leftJoin(
                'order_documents_locations',
                'order_documents_location_relation.location_id', '=', 'order_documents_locations.id'
            )
            ->where('order_documents_locations.code', $code)
            ->get();
    }
}