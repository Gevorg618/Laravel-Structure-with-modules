<?php

namespace App\Models\Integrations\MercuryNetwork;

use App\Models\BaseModel;
use App\Models\Customizations\{LoanType,LoanReason, Type};

class MercuryEquityEdge extends BaseModel
{
     /**
   * The table associated with the model.
   *
   * @var string
   */
    protected $table = 'mercury_custom_products';

    protected $fillable = ['product_name', 'appr_type', 'loan_reason', 'loan_type'];
    
    public $timestamps = false;

    public static function getAll()
    {
        return self::orderBy('product_name', 'ASC')->get();
    }

    public  function apprType()
    {
        return $this->hasOne(Type::class, 'id', 'appr_type');
    }

    public  function loanType()
    {
        return $this->hasOne(LoanType::class, 'id', 'loan_type');
    }

    public  function loanReason()
    {
        return $this->hasOne(LoanReason::class, 'id', 'loan_reason');
    }
}
