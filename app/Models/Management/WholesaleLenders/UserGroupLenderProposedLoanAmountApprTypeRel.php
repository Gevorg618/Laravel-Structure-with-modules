<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderProposedLoanAmountApprTypeRel extends BaseModel
{
    protected $table = 'user_group_lender_proposed_loan_amount_appr_type_rel';

    protected $fillable = [
        'proposed_id',
        'appr_type_id'
    ];

    public $timestamps = false;
}
