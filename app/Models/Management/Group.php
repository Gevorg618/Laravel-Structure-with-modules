<?php
    
namespace App\Models\Management;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends BaseModel
{
    use SoftDeletes;
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'user_group';
    
    /**
     * Fillable Fields
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'is_default'
    ];
    
    public function beforeSave()
    {
        $this->gkey = getCode($this->title);
        
        return parent::beforeSave();
    }
    
    /**
     * We don't use saved and updated timestamps
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * Soft Delete column
     *
     * @var array
     */
    protected $dates = ['deleted_at'];
    
    /**
     * Store user group
     *
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $group = Group::findOrNew($request->id);
        $group->fill($request->all());
        $group->save();
        
        return true;
    }
    
    /**
     * Check if user group can be deleted
     *
     * @return bool
     */
    public function isProtected()
    {
        return $this->is_protected;
    }
    
}
