<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderProposedLoanAmountAddendaRel extends BaseModel
{
    protected $table = 'user_group_lender_proposed_loan_amount_addenda_rel';

    protected $fillable = [
        'proposed_id',
        'addenda_id'
    ];

    public $timestamps = false;
}
