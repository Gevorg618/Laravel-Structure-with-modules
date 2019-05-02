<?php

namespace App\Models\Tiger;

use App\Models\BaseModel;

class AmcUserAssociation extends BaseModel
{

    /**
     * Database Connection
     * @var string
     */
    protected $connection = 'tiger';
    
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'amc_user_association';
    
    protected $fillable = [
        'amc_id',
        'email'
    ];
    
    public $timestamps = false;
    
}