<?php
    
namespace App\Models\DocuVault;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Type extends BaseModel
{
    use SoftDeletes;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'docuvault_appr_type';
    
    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'is_active'
    ];
    
    /**
     * Soft Delete column
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Check do we have new appraisal
     */
    public function beforeSave()
    {
        if (!$this->exists) {
            $this->code = getCode($this->title);
        }
        return parent::beforeSave();
    }
    
    /**
     * Store property type
     *
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $appraisal = Type::findOrNew($request->id);
    
        $appraisal->fill($request->all());
        $appraisal->save();
        
        return true;
    }
    
}
