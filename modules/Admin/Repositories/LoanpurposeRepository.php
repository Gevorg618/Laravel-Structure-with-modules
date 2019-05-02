<?php


namespace Admin\Repositories;


use App\Models\Customizations\LoanReason;

class LoanpurposeRepository
{
    /**
     * Object of Loanpurpose class
     *
     * @var loanpurpose
     */
    private $loanpurpose;


    /**
     * LoanpurposeRepository constructor.
     */
    public function __construct()
    {
        $this->loanpurpose = new LoanReason();
    }


    /**
     * @return mixed
     */
    public function getLoanpurpose()
    {
        return $this->loanpurpose->orderBy('descrip')->pluck('descrip', 'id');
    }


    /**
     * @param $ids
     * @return mixed
     */
    public function loanPurposeListAddRemoveFromClient($ids)
    {
        return $this->loanpurpose->orderBy('descrip', 'asc')
            ->whereIn('id', $ids)->pluck('descrip','id')->toArray();
    }
}
