<?php

namespace App\Models\Clients;
use App\Models\BaseModel;

class UserGroupFile extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_group_files';


    protected $fillable = [
        'group_id',
        'docname',
        'filename',
        'is_imported',
        'file_location',
        'created_at',
        'created_by',
        'is_aws',
        'file_size'

    ];


    public $timestamps = false;


    /**
     * Get the UserGroupFile that owns the Client.
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Clients\Client', 'group_id');
    }
}
