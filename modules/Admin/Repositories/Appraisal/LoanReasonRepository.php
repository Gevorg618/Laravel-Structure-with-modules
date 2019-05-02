<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Customizations\LoanReason;

class LoanReasonRepository
{
    private $loanReason;

    /**
     * LoanTypesRepository constructor.
     */
    public function __construct()
    {
        $this->loanReason = new LoanReason();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function loanReasons()
    {
        return $this->loanReason->get();
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getLoanPurposeList()
    {
        return $this->loanReason->pluck('descrip', 'id');
    }


    /**
     * @return mixed
     */
    public function loanReasonsList()
    {
        return $this->loanReason->select('id', 'descrip')->orderBy('descrip', 'ASC')->get();
    }
}
