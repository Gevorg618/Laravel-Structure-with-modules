<?php

namespace Modules\Admin\Repositories\ApprType;


use App\Models\ApprType\ApprType;
use DB;

class ApprTypeRepository
{
    /**
     * Object of ApprType class
     *
     * @var $apprType
     */
    private $model;

    /**
     * ClientRepository constructor.
     */
    public function __construct()
    {
        $this->model = new ApprType();
    }


    /**
     * get all apprTypes
     *
     * @return collection
     */
    public function apprTypes()
    {
        return $this->model->get();
    }


    /**
     * get  apprTypes
     *
     * @return collection
     */
    public  function getApprTypeList($where)
    {
        $rows = $this->model
            ->select(DB::raw('CONCAT(form, "", descrip) AS form_descript'), 'id','descrip','short_descrip', 'form', 'order')
            ->where($where)
            ->orderBy('form_descript')->get();
        foreach($rows as $row) {
            $types[$row->id] = $row->form ? ($row->form . ' - ' . $row->descrip) : $row->descrip;
        }
        return $types;
    }


    /**
     * @param $ids
     * @return mixed
     */
    public function apprTypeListAddRemoveFromClient($ids)
    {
        $rows = $this->model
            ->select(DB::raw('CONCAT(form, "", descrip) AS form_descript'), 'id','descrip','short_descrip', 'form', 'order')
            ->where('active', '=', 'Y')
            ->whereIn('id', $ids)
            ->orderBy('form_descript')->get();
        foreach($rows as $row) {
            $types[$row->id] = $row->form ? ($row->form . ' - ' . $row->descrip) : $row->descrip;
        }
        return $types;
    }







}
