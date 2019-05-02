<?php

namespace App\Models\Users;


use App\Models\Appraisal\Order as AltOrder;
use App\Models\Appraisal\AltSubOrder;
use App\Models\Appraisal\Order;
use App\Models\Appraisal\OrderLog;
use App\Models\Appraiser\AppraiserGroup;
use App\Models\Clients\Client;
use App\Models\UserGroup;
use Carbon;
use App\Models\BaseModel;
use App\Hash\Hasher as Hash;
use App\Models\Users\Certification;
use App\Models\Documents\UserDoc;
use App\Models\Management\ASCLicense;
use App\Models\Management\FHALicense;
use Illuminate\Auth\Authenticatable;
use App\Models\Users\UserGroupRelation;
use Illuminate\Notifications\Notifiable;
use App\Models\Appraisal\AppraiserPayment;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\User as AuthUser;
use Illuminate\Foundation\Auth\Access\Authorizable;
use App\Models\Management\WholesaleLenders\UserGroupLenderUserManager;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, Notifiable;

    const USER_TYPE_ADMIN = 1;
    const USER_TYPE_APPRAISER = 4;
    const USER_TYPE_CLIENT = 5;
    const USER_TYPE_AGENT = 14;


    const DOCUMENT_TYPE_W9 = 'w9';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    protected $fillable = [
        'email',
        'active',
        'password',
        'password_confirmation',
        'user_type',
        'admin_priv',
        'register_date',
        'last_updated',
        'groupid'
    ];

    protected $appends = [
      'fullname',
      'initials',
      'isGroupManager',
      'isGroupSupervisor',
      'isWholesaleLenderManager',
      'isDocuvaultEnabled',
      'isAVMEnabled'
    ];

    protected $primaryKey = 'id';

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'user';
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'email' => $this->email,
            'firstname' => $this->userData->firstname ?? null,
            'lastname' => $this->userData->lastname ?? null,
            'id' => $this->id,
        ];

        return $array;
    }

    public function beforeSave()
    {
        if($this->isNewRecord) {
            $this->register_date = Carbon::now();
        }

        return parent::beforeSave();
    }

    public function getPublicInfo()
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'fullname' => $this->fullname,
        ];
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string
     */
    public function getRememberToken()
    {
        return $this->{$this->getRememberTokenName()};
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        $this->{$this->getRememberTokenName()} = $value;
    }

    public function getFullNameAttribute()
    {
        return $this->userData
            ? trim(ucwords(strtolower($this->userData->firstname . ' ' . $this->userData->lastname)))
            : $this->email;
    }

    public function getInitialsAttribute()
    {
        if ($this->userData) {
            $first = strtoupper(substr($this->userData->firstname, 0, 1));
            $last = strtoupper(substr($this->userData->lastname, 0, 1));
            return trim($first. $last);
        }

        return $this->email;
    }


    public function userData()
    {
        return $this->hasOne('App\Models\Users\UserData', 'user_id');
    }

    public function userNotes()
    {
        return $this->hasMany(UserNote::class, 'userid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderLogs()
    {
        return $this->hasMany(OrderLog::class, 'userid');
    }

    /**
     * Excluded groups
     * @return $this
     */
    public function excludedGroups()
    {
        return $this->hasMany(UserExclude::class, 'apprid')->with('groupData');
    }

    public function excludedProfiles()
    {
        return $this->belongsToMany(ExcludedProfiles::class,'user_group_lender_exclude_appraiser','userid','lenderid')->withPivot('created_date');
    }

    public function userType()
    {
        return $this->hasOne('App\Models\Management\UserType', 'id', 'user_type');
    }

    public function remoteFiles()
    {
        return $this->hasMany('App\Models\Tools\RemoteFile');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Client::class, 'groupid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'groupid');
    }

    /**
     * Return list of admin members
     * @return array
     */
    public static function getAdminMembers() {
        return self::where('active', 'Y')->where('user_type', self::USER_TYPE_ADMIN)->with('userData')->get()->sortBy('userData.firstname')->each(function($u){
            $u->fullname = $u->getFullNameAttribute();
        });
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    public function isActive()
    {
        return $this->active == 'Y';
    }

    public function isClient()
    {
        return $this->user_type == static::USER_TYPE_CLIENT;
    }

    public function isVendor()
    {
        return $this->isAgent() || $this->isAppraiser();
    }

    public function isAgent()
    {
        return $this->user_type == static::USER_TYPE_AGENT;
    }

    public function isAppraiser()
    {
        return $this->user_type == static::USER_TYPE_APPRAISER;
    }

    public function isAdmin()
    {
        return $this->user_type == static::USER_TYPE_ADMIN;
    }

    /**
     * Check to see if DocuVault is enabled
     */
    public function hasDocuvaultEnabled()
    {
      if(!$this->isClient()) {
        return false;
      }

      // If we are a wholesale lender
      // Check if one of the lenders have this enabled
      if ($this->isWholesaleLenderManager()) {
          $lenders = $this->wholesaleLendersManager()->joinLenders()->where('enable_docuvault', 1)->count();
          if($lenders) {
            return true;
          }
      }

      // Current group
      if($this->group && $this->group->enable_docuvault) {
          return true;
      }

      return false;
    }

    public function getIsDocuvaultEnabledAttribute()
    {
      return $this->hasDocuvaultEnabled();
    }

    /**
     * Check if the client has AVM enabled
     */
    public function hasAVMEnabled()
    {
      if(!$this->isClient()) {
        return false;
      }

      // If we are a wholesale lender
      // Check if one of the lenders have this enabled
      if ($this->isWholesaleLenderManager()) {
          $lenders = $this->wholesaleLendersManager()->joinLenders()->where('enable_avm', 1)->count();
          if($lenders) {
            return true;
          }
      }

      // Current group
      if($this->group && $this->group->enable_avm) {
          return true;
      }

      return false;
    }

    public function getIsAVMEnabledAttribute()
    {
      return $this->hasAVMEnabled();
    }

    /**
     * Check if the user is the group manager
     * we validate by checking if the users group exists in the
     * groups relation
     */
    public function isGroupManager()
    {
      return (bool) (count($this->groups) && $this->groups->where('group_id', $this->groupid)->count());
    }

    public function getIsGroupManagerAttribute()
    {
      return $this->isGroupManager();
    }

    /**
     * Check if the user is the group supervisor
     * we validate by checking if the user has any related groups
     */
    public function isGroupSupervisor()
    {
      return (bool) count($this->groups);
    }

    public function getIsGroupSupervisorAttribute()
    {
      return $this->isGroupSupervisor();
    }

    public function getIsAnyManagerAttribute()
    {
      return $this->isGroupManager || $this->isGroupSupervisor;
    }

    /**
     * check if a user is a wholesale lender manager
     * we validate by checking the users existence
     * in the wholesale lender manager table
     */
    public function isWholesaleLenderManager()
    {
      return (bool) count($this->wholesaleLendersManager);
    }

    public function getIsWholesaleLenderManagerAttribute()
    {
      return $this->isWholesaleLenderManager();
    }

    public function groupshasMany()
    {
      return $this->hasMany(UserGroupRelation::class, 'user_id');
    }

    public function wholesaleLendersManager()
    {
      return $this->hasMany(UserGroupLenderUserManager::class, 'userid');
    }

    public function hasAdminAccess()
    {
        return $this->isActive() && $this->isAdmin();
    }

    public function scopeAdmins($query)
    {
        return $query->with('userData')->where('user_type', static::USER_TYPE_ADMIN);
    }

    public function scopeAppraisers($query)
    {
        return $query->with('userData')->where('user_type', static::USER_TYPE_APPRAISER);
    }

    public function scopeJoinUserData($query)
    {
        return $query->join('user_data', 'user_data.user_id', '=', 'user.id');
    }

    public function scopeOfState($query, $state)
    {
        return $query->where('user_data.state', $state)
                    ->orWhere('user_data.comp_state', $state);
    }

    public function scopeByEmail($query, $email)
    {
        return $query->where('email', $email);
    }

    public function ascLicenses($state)
    {
        $certifications = Certification::ofUser($this)->ofState($state)->get();

        $fha = FHALicense::ofUser($this)
                            ->ofState($state)
                            ->ofLicenseNumbers($certifications->pluck('cert_num')->all())
                            ->get();

        $licenseNumbers = collect($certifications->pluck('cert_num')->all())
                                ->merge($fha->pluck('license_number')->all())
                                ->unique()
                                ->all();

        $licenses = ASCLicense::ofUser($this)
                            ->ofState($state)
                            ->ofLicenseNumbers($licenseNumbers)
                            ->get();

        return $licenses;
    }

    public function fhaLicenses($state)
    {
        $certifications = Certification::ofUser($this)
                                        ->ofState($state)
                                        ->get();

        $licenses = ASCLicense::ofUser($this)
                            ->ofState($state)
                            ->ofLicenseNumbers($certifications->pluck('cert_num')->all())
                            ->get();

        $licenseNumbers = collect($certifications->pluck('cert_num')->all())
                                ->merge($licenses->pluck('lic_number')->all())
                                ->unique()
                                ->all();

        $fha = FHALicense::ofUser($this)
                            ->ofState($state)
                            ->ofLicenseNumbers($licenseNumbers)
                            ->get();

        return $fha;
    }

    /**
     * user appraiser in many groups
     *
     * @return mixed
     * @author CodeIdea
     */
    public function appraiserGroups()
    {
        return $this->belongsToMany(AppraiserGroup::class, 'appr_group_user', 'userid', 'groupid')->with('userData');
    }

    /**
     * get user all data
     *
     * @return mixed
     */
    public static function getUserAllData($id)
    {
        return self::where('id', $id)->with('userData')->first();
    }

    /**
     * get user all data
     *
     * @return mixed
     */
    public function dashboardDelayOrder()
    {
        return $this->hasMany('App\Models\Appraisal\ApprDashboardDelayOrder', 'created_by');
    }

     /**
     * get user all data
     * table appr_user_view
     * @return mixed
     */
    public function apprUserView()
    {
        return $this->hasMany('App\Models\Appraisal\ApprUserView', 'userid');
    }

     /**
     * get user all data
     * table appr_dashboard_transfer_to
     * @return mixed
     */
    public function apprDashboardToTransfers()
    {
        return $this->hasMany('App\Models\Appraisal\ApprDashboardTransferTo', 'fromuser');
    }

    /**
     *
     * @return collection
     */
    public function teamMembers()
    {
        return $this->belongsToMany('App\Models\Management\AdminTeamsManager\AdminTeam', 'admin_team_member', 'user_id', 'team_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lenders()
    {
        return $this->belongsToMany(Lender::class, 'user_group_lender_user_manager', 'userid', 'lenderid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany(UserGroup::class, 'user_group_relation', 'user_id', 'group_id');
    }

    /**
     * @param int $size
     * @return string
     */
    public function getAvatar($size=80) {
        // Gravatar
        $link = sprintf("https://s.gravatar.com/avatar/%s?s=%s&d=%s", md5( strtolower( trim( $this->email ) ) ), $size, urlencode('mm'));
        return $link;
    }

    /**
     * @return int
     */
    public function getOrdersAccepted() {
        return Order::where('acceptedby', $this->id)
            ->where('status', '!=', Order::TEMP_STATUS)->count();
    }

    /**
     * @return int
     */
    public function getApprOrdersCompleted() {
        return Order::where('acceptedby', $this->id)
            ->whereIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_HOLD_UW_CONDITIONS,
                Order::STATUS_HOLD_UW_APPROVAL,
            ])->count();
    }

    /**
     * @return int
     */
    public function getALOrdersAccepted() {
        $result = AltOrder::where('acceptedby', $this->id)->count();
        $result += AltSubOrder::where('acceptedby', $this->id)->count();
        return $result;
    }

    /**
     * @return int
     */
    public function getALOrdersCompleted() {
        $result = AltOrder::where('acceptedby', $this->id)
            ->where('status', AltOrder::STATUS_COMPLETE)->count();
        $result += AltSubOrder::where('acceptedby', $this->id)
            ->where('status', AltOrder::STATUS_COMPLETE)->count();
        return $result;
    }

    /**
     * @return int
     */
    public function getOrdersPlaced() {
        return Order::where('orderedby', $this->id)
            ->whereNotIn('status', [
                Order::TEMP_STATUS,
                Order::STATUS_CANCELLED,
                Order::STATUS_CANCELLED_TRIP_FEE,
            ])->count();
    }

    /**
     * @return int
     */
    public function getOrdersCompleted()
    {
        return Order::where('orderedby', $this->id)
            ->whereIn('status', [
                Order::STATUS_APPRAISAL_COMPLETED,
                Order::STATUS_HOLD_UW_CONDITIONS,
                Order::STATUS_HOLD_UW_APPROVAL,
            ])->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groupsBySales()
    {
        return $this->hasMany('App\Models\Clients\Client', 'salesid');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lendersBySales()
    {
        return $this->hasMany('App\Models\Management\WholesaleLenders\UserGroupLender', 'salesid');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function appraiserPayments()
    {
        return $this->hasMany(AppraiserPayment::class, 'apprid', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function w9()
    {
        return $this->hasOne(UserDoc::class, 'userid')->ofType(static::DOCUMENT_TYPE_W9)->orderByDesc('id');
    }
}
