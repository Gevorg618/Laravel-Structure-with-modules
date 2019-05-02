<?php
    
namespace App\Models\Tiger;

use App\Models\BaseModel;

class ClientOptionValue extends BaseModel
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
    protected $table = 'client_option_value';
    
    public $timestamps = false;
    
}