<?php

namespace Modules\Admin\Repositories\Lenders;
use App\Models\Management\WholesaleLenders\UserGroupLender;

class LenderRepository
{

	/**
     * Object of Lender class
     *
     * @var $lender
     */
    private $lender;

    /**
     * StatesRepository constructor.
     */
    public function __construct()
    {
        $this->lender = new UserGroupLender();
    }

	/**
     * get all lesale Lenders
     *
     * @return collection
     */
    public function lesaleLenders()
    {
        return $this->lender->where('default_watch_list', 1)->select('lender', 'id')->get();
    }



    public function lesaleLendersList()
    {
        return $this->lender->orderBy('lender', 'ASC')->pluck('lender', 'id');
    }


    /**
     * @param $ids
     * @return mixed
     */
    public function lesaleLendersListAddRemoveFromClient($ids)
    {
        return $this->lender->orderBy('lender', 'asc')
            ->whereIn('id', $ids)->pluck('lender','id')->toArray();
    }
}
