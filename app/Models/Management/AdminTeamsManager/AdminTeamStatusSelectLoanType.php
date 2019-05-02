<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;

class AdminTeamStatusSelectLoanType extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_team_status_select_loan_type';

    protected $fillable = [
        'team_id',
        'loan_id'
    ];

    public $timestamps = false;

    public static function getSelectedLoanTypes($id)
    {
        return self::select('loan_id')->where('team_id', $id)->get();
    }
}
