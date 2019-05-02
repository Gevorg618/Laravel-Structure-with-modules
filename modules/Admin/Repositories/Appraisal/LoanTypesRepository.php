<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Customizations\LoanType;

class LoanTypesRepository
{
    private $loanType;

    /**
     * LoanTypesRepository constructor.
     */
    public function __construct()
    {
        $this->loanType = new LoanType();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function loanTypes()
    {
        return $this->loanType->get();
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function getLoanTypeList()
    {
        return $this->loanType->pluck('descrip', 'id');
    }


    /**
     * @return mixed
     */
    public function loanTypesList()
    {
        return $this->loanType->orderBy('descrip', 'asc')
            ->pluck('descrip','id');
    }


    /**
     * @param $ids
     * @return mixed
     */
    public function loanTypesListAddRemoveFromClient($ids)
    {
        return $this->loanType->orderBy('descrip', 'asc')
            ->whereIn('id', $ids)->pluck('descrip','id')->toArray();
    }
}
