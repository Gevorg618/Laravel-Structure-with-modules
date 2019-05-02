<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderProposedLoanAmountStateRel extends BaseModel
{
    protected $table = 'user_group_lender_proposed_loan_amount_state_rel';

    protected $fillable = [
        'proposed_id',
        'state'
    ];

    public $timestamps = false;
}
