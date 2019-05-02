<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Appraisal\DocuVaultOrder;

class DocuVaultOrderRepository
{
    private $docVaultOrder;

    /**
     * DocuVaultOrderRepository constructor.
     */
    public function __construct()
    {
        $this->docVaultOrder = new DocuVaultOrder();
    }

    /** Order Functions **/
    public function getDocuVaultOrderById($id)
    {
        $row = $this->docVaultOrder->where('id', $id)->first();
        return $row && $row->id ? $row : '';
    }
}
