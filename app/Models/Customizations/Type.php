<?php

namespace App\Models\Customizations;

use DB;
use App\Models\BaseModel;

class Type extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'appr_type';

    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    protected $fillable = ['descrip', 'short_descrip', 'form', 'order', 'mismo_label', 'fha', 'active', 'position', 'is_protected', 'code', 'order_placement_comments', 'baseprice_con', 'baseprice_fha', 'is_default', 'ead_form', 'require_xml', 'require_pdf', 'realview_type', 'is_allowed_license_bypass'];

    /**
     * @return string
     */
    public function getTitleAttribute()
    {
        return $this->form ? trim($this->form . ' - ' . $this->descrip) : $this->descrip;
    }

    public function allTypes() {
        return $this->orderBy(DB::raw('CONCAT(form,"",descrip)'), 'ASC')->get();
    }

    /**
     * @param $query
     * @param bool|string $description
     * @return mixed
     */
    public function scopeOfDescription($query, $description = false)
    {
        if ($description) {
            return $query->where('form', 'like', '%' . $description . '%')
                ->orWhere('appr_type.short_descrip', 'like', '%' . $description . '%');
        }
    }

    /**
     * Connection to order_documents_appr_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function apprTypeOrderDocument()
    {
        return $this->belongsToMany('App\Models\OrderDocuments\Document', 'order_documents_appr_type',  'appr_type_id','file_id');
    }
}
