<?php
    
namespace App\Models\Tiger;

use App\Models\BaseModel;

class ClientOption extends BaseModel
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
    protected $table = 'client_option';
    
    public $timestamps = false;
    
    public function values()
    {
        
    }
    
}