<?php

namespace App\Models\AVM;

use Carbon\Carbon;
use App\Models\BaseModel;
use App\Models\Users\User;
use App\Models\Management\ZipCode;
use App\Models\Appraisal\Order as AppraisalOrder;

class Order extends BaseModel
{
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'avm';

    protected $fillable = [];

    protected $appends = ['address', 'shortapprtypename'];

    /**
     * @return string
     */
    public function getShortApprTypeNameAttribute()
    {
        return $this->product ? $this->product : config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getShortAddressAttribute()
    {
        return trim($this->propaddress1 . ', ' . $this->propcity);
    }

    /**
     * @return string|null
     */
    public function getClientNameAttribute()
    {
        return $this->client ? $this->client->fullname : null;
    }

    /**
     * @return string|null
     */
    public function getStatusNameAttribute()
    {
        return $this->orderStatus ? $this->orderStatus->descrip : null;
    }

    /**
     * @return false|string
     */
    public function getFormatOrderDateAttribute()
    {
        return date('m/d/y', strtotime($this->ordereddate));
    }

    /**
     * @return string
     */
    public function getAddressAttribute()
    {
        return \App\Helpers\Address::getFullAddress(
            $this->propaddress1,
            $this->propaddress2,
            $this->propcity,
            $this->propstate,
            $this->propzip
        );
    }

    /**
     * @return string
     */
    public function getBalanceAttribute()
    {
        $result = $this->invoicedue - $this->paid_amount;
        return sprintf("%.2f", $result);
    }

    public function getPlacedDateAttribute()
    {
        return formatDate($this->ordereddate);
    }


    /**
     * @param $query
     * @param bool|array $states
     * @return mixed
     */
    public function scopeOfStates($query, $states = false)
    {
        if ($states !== false) {
            return $query->whereIn('propstate', $states);
        }
    }

    /**
     * @param $query
     * @param bool|string $borrower
     * @return mixed
     */
    public function scopeOfBorrower($query, $borrower = false)
    {
        if ($borrower !== false) {
            return $query->where('borrower', 'like', '%' . $borrower . '%');
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        $query->where('status', '!=', AppraisalOrder::STATUS_TEMP);
    }

    public function scopeOfStatusFilter($query, $type = null)
    {
        $statuses = explode(',', setting('appr_order_ignore_statuses'));
        if ($type == 'active') {
            $query->whereNotIn($this->qualifyColumn('status'), array_unique(array_merge($statuses, [AppraisalOrder::STATUS_APPRAISAL_COMPLETED, AppraisalOrder::STATUS_CANCELLED_TRIP_FEE])));
        } elseif ($type == 'completed') {
            $query->whereIn($this->qualifyColumn('status'), [
              AppraisalOrder::STATUS_APPRAISAL_COMPLETED,
              AppraisalOrder::STATUS_UW_CONDITION,
              AppraisalOrder::STATUS_UW_APPROVAL,
              AppraisalOrder::STATUS_RECONSIDERATION
        ]);
        } else {
            $query->whereNotIn($this->qualifyColumn('status'), $statuses);
        }
    }

    public function scopeOfPlacedBy($query, $id)
    {
        $query->where($this->qualifyColumn('orderedby'), $id);
    }


    /**
     * @param $query
     * @param bool|string $search
     * @return mixed
     */
    public function scopeOfSearch($query, $search = false)
    {
        if ($search) {
            return $query->orWhereHas('userData', function ($query) use ($search) {
                $query->ofName($search);
            })
                ->orWhere($this->qualifyColumn('propaddress1'), 'like', '%' . $search . '%')
                ->orWhere($this->qualifyColumn('propcity'), 'like', '%' . $search . '%')
                ->orWhere($this->qualifyColumn('propstate'), 'like', '%' . $search . '%')
                ->orWhere($this->qualifyColumn('propzip'), 'like', '%' . $search . '%')
                ->orWhere($this->qualifyColumn('borrower'), 'like', '%' . $search . '%')
                ->orWhere($this->qualifyColumn('loanrefnum'), 'like', '%' . $search . '%')
                ->orWhere($this->qualifyColumn('id'), 'like', '%' . $search . '%');
        }
    }

    // --------------------

    /**
     * Connection to User table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Users\User', 'orderedby');
    }

    /**
     * Connection to status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function orderStatus()
    {
        return $this->belongsTo('App\Models\Customizations\Status', 'status');
    }

    /**
     * Connection to user_group_lender table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lenderRecord()
    {
        return $this->belongsTo('App\Models\Management\WholesaleLenders\UserGroupLender', 'lender_id');
    }

    /**
     * Connection to user_groups table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupData()
    {
        return $this->belongsTo('App\Models\Clients\Client', 'groupid')
            ->with('adminTeamClient');
    }


    /**
     * Connection to amc_registration table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function registration()
    {
        return $this->hasOne('App\Models\Customizations\AMCLicense', 'state', 'propstate');
    }

    /**
     * Connection to user_groups table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function apprClient()
    {
        return $this->hasMany('App\Models\Clients\Client', 'id', 'groupid');
    }

    /**
     * Connection to user_data table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function userData()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id', 'orderedby');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function zipCode()
    {
        return $this->hasOne(ZipCode::class, 'zip_code', 'propzip');
    }

    /*
     * Get Order  Data
     * @return mixed
     */
    public static function getOrderById($id)
    {
        return self::where('id', $id)->first();
    }
}
