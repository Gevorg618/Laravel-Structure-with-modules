<?php

namespace App\Models\Appraisal\QC;


/**
 * Class ChecklistCategory
 * @package App\Models\Appraisal\QC
 */
class ChecklistCategory extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'appr_qc_checklist_category';

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @param array $request
     * @return $this
     */
    public function setData($request = [])
    {
        $this->title = $request['title'];
        $this->is_active = $request['is_active'];
        $this->ord = $request['ord'];
        return $this;
    }
}
