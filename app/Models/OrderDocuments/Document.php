<?php

namespace App\Models\OrderDocuments;

use App\Models\BaseModel;
use App\Models\Users\User;

class Document extends BaseModel
{
    protected $table = 'order_documents';

    public $timestamps = false;

     /**
     * @var array
     */
    protected $fillable = ['file_name', 'is_active', 'is_client_visible' , 'is_appr_visible', 'created_by', 'created_date', 'file_location', 'file_size'];

    /**
     * @param $query
     * @param string|bool $state
     * @return mixed
     */
    public function scopeWithState($query, $state = false)
    {
        if ($state) {
            return $query->whereHas('state', function ($query) use ($state) {
                $query->ofState($state);
            });
        }
    }

    /**
     * @param $query
     * @param int|bool $type
     * @return mixed
     */
    public function scopeWithType($query, $type = false)
    {
        if ($type) {
            return $query->whereHas('type', function ($query) use ($type) {
                $query->ofType($type);
            });
        }
    }

    /**
     * @param $query
     * @param int|bool $loanType
     * @return mixed
     */
    public function scopeWithLoanType($query, $loanType = false)
    {
        if ($loanType) {
            return $query->whereHas('loanType', function ($query) use ($loanType) {
                $query->ofType($loanType);
            });
        }
    }

    /**
     * @param $query
     * @param int|bool $loanReason
     * @return mixed
     */
    public function scopeWithLoanReason($query, $loanReason = false)
    {
        if ($loanReason) {
            return $query->whereHas('loanReason', function ($query) use ($loanReason) {
                $query->ofType($loanReason);
            });
        }
    }

    /**
     * @param $query
     * @param int|bool $propType
     * @return mixed
     */
    public function scopeWithPropType($query, $propType = false)
    {
        if ($propType) {
            return $query->whereHas('propType', function ($query) use ($propType) {
                $query->ofType($propType);
            });
        }
    }

    /**
     * @param $query
     * @param int|bool $status
     * @return mixed
     */
    public function scopeWithOccStatus($query, $status = false)
    {
        if ($status) {
            return $query->whereHas('occStatus', function ($query) use ($status) {
                $query->ofType($status);
            });
        }
    }

    // ------------------------

    /**
     * Connection to order_documents_state table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function state()
    {
        return $this->hasOne('App\Models\OrderDocuments\State', 'file_id');
    }

    /**
     * Connection to order_documents_appr_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function type()
    {
        return $this->hasOne('App\Models\OrderDocuments\Type', 'file_id');
    }

    /**
     * Connection to order_documents_loan_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanType()
    {
        return $this->hasOne('App\Models\OrderDocuments\LoanType', 'file_id');
    }

    /**
     * Connection to order_documents_loan_reason table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function loanReason()
    {
        return $this->hasOne('App\Models\OrderDocuments\LoanReason', 'file_id');
    }

    /**
     * Connection to order_documents_prop_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function propType()
    {
        return $this->hasOne('App\Models\OrderDocuments\PropType', 'file_id');
    }

    /**
     * Connection to order_documents_occ_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function occStatus()
    {
        return $this->hasOne('App\Models\OrderDocuments\OccStatus', 'file_id');
    }

    // ------------------------

    public function getFullNameAttribute()
    {
        $parts = explode('.', $this->file_location);

        return $this->file_name . '.' . end($parts);
    }


    /**
     * Connection to order_documents_lender table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function lenderPivot()
    {
        return $this->belongsToMany('App\Models\Management\WholesaleLenders\UserGroupLender', 'order_documents_lender', 'file_id');
    }

    /**
     * Connection to order_documents_user_group table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function clientPivot()
    {
        return $this->belongsToMany('App\Models\Clients\Client', 'order_documents_user_group', 'file_id', 'user_group_id');
    }

    /**
     * Connection to order_documents_location_relation table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function locationPivot()
    {
        return $this->belongsToMany('App\Models\Documents\OrderLocation', 'order_documents_location_relation', 'file_id', 'location_id');
    }

    /**
     * Connection to order_documents_state table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function statePivot()
    {
        return $this->belongsToMany('App\Models\Geo\State', 'order_documents_state', 'file_id', 'state');
    }

    /**
     * Connection to order_documents_appr_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function apprTypePivot()
    {
        return $this->belongsToMany('App\Models\Customizations\Type', 'order_documents_appr_type', 'file_id', 'appr_type_id');
    }

    /**
     * Connection to order_documents_loan_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function loanTypePivot()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanType', 'order_documents_loan_type', 'file_id', 'type_id');
    }

    /**
     * Connection to order_documents_loan_reason table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function loanReasonPivot()
    {
        return $this->belongsToMany('App\Models\Customizations\LoanReason', 'order_documents_loan_reason', 'file_id', 'type_id');
    }

    /**
     * Connection to order_documents_prop_type table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function loanPropertyPivot()
    {
        return $this->belongsToMany('App\Models\Customizations\PropertyType', 'order_documents_prop_type', 'file_id', 'type_id');
    }

    /**
     * Connection to order_documents_occ_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function occStatusPivot()
    {
        return $this->belongsToMany('App\Models\Customizations\OccupancyStatus', 'order_documents_occ_status', 'file_id', 'type_id');
    }

    /**
     * docs created by user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userData');
    }
    
}
