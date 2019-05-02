<?php

namespace App\Models\Appraisal;

use App\Models\BaseModel;

class AppraisalStatusSelect extends BaseModel
{
    const FLAG_BORROWER_CONTACT = 'borrower_contact';
    const FLAG_CLIENT_APPROVAL = 'client_approval';
    const FLAG_TILA = 'tila';

    public static $flags = [self::FLAG_BORROWER_CONTACT => 'Borrower Contact Requried', self::FLAG_CLIENT_APPROVAL => 'Awaiting Client Approval', self::FLAG_TILA => 'Tila Authorization'];
}
