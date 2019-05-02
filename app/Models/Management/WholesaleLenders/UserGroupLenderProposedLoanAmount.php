<?php

namespace App\Models\Management\WholesaleLenders;

use App\Models\BaseModel;

class UserGroupLenderProposedLoanAmount extends BaseModel
{
    protected $table = 'user_group_lender_proposed_loan_amount';

    protected $fillable = [
        'lender_id',
        'title',
        'range_start',
        'range_end',
        'amount',
    ];

    public $timestamps = false;
}
