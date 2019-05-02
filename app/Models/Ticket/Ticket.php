<?php

namespace App\Models\Ticket;

use DB;
use Carbon\Carbon;
use App\Models\BaseModel;

class Ticket extends BaseModel
{
    const PRIORITY_LOW = 1;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_MEDIUM = 3;
    const PRIORITY_HIGH = 4;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets';

    protected $fillable = [
        'locked_by',
        'locked_date',
        'closedid',
        'closed_date',
        'priority',
        'assignid',
        'assigntype',
        'orderid',
        'type',
        'close_start',
        'close_end',
    ];

    public $timestamps = false;

    protected $appends = ['hash'];

    public function getHashAttribute()
    {
        return sha1($this->id);
    }

    public function scopeOfHashedId($query, $hash)
    {
        $query->where(DB::raw("SHA1(id)"), $hash);
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'ticket';
    }

    /**
     * Connection to appr_order table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function apprOrder()
    {
        return $this->belongsTo('App\Models\Appraisal\Order', 'orderid');
    }

    /**
     * Connection to alt_order table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function altOrder()
    {
        return $this->belongsTo('App\Models\AlternativeValuation\Order', 'orderid');
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function closedByUser()
    {
        return $this->belongsTo('App\Models\Users\User', 'closedid');
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lockedByUser()
    {
        return $this->belongsTo('App\Models\Users\User', 'locked_by');
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdByUser()
    {
        return $this->belongsTo('App\Models\Users\User', 'userid');
    }

    /**
     * Connection to tickets_category_rel table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function categoryRel()
    {
        return $this->hasOne('App\Models\Ticket\CategoryRel', 'ticket_id');
    }

    /**
     * Connection to tickets_status_rel table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statusRel()
    {
        return $this->hasOne('App\Models\Ticket\StatusRel', 'ticket_id');
    }

    /**
     * Connection to tickets_content table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contentText()
    {
        return $this->hasOne('App\Models\Ticket\Content', 'ticket_id')->where('type', 'text');
    }

    /**
     * Connection to tickets_content table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function contentHtml()
    {
        return $this->hasOne('App\Models\Ticket\Content', 'ticket_id')->where('type', 'html');
    }

    /**
     * Connection to tickets_participate table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participates()
    {
        return $this->hasMany('App\Models\Ticket\Participate', 'ticket_id');
    }

    /**
     * Connection to tickets_comments table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Ticket\Comment', 'ticket_id');
    }

    /**
     * Connection to tickets_comments table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function publicComments()
    {
        return $this->comments()->where('is_public', 1);
    }

    /**
     * Connection to tickets_activity table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities()
    {
        return $this->hasMany('App\Models\Ticket\Activity', 'ticket_id');
    }

    /**
     * Connection to tickets_files table
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files()
    {
        return $this->hasMany('App\Models\Ticket\File', 'tixid');
    }

    /**
     * Connection to tickets_category table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(
            'App\Models\Ticket\Category',
            'tickets_category_rel',
            'ticket_id',
            'category_id'
        );
    }

    /**
     * Connection to tickets_status table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function statuses()
    {
        return $this->belongsToMany(
            'App\Models\Ticket\Status',
            'tickets_status_rel',
            'ticket_id',
            'status_id'
        );
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function participants()
    {
        return $this->belongsToMany(
            'App\Models\Users\User',
            'tickets_participate',
            'ticket_id',
            'user_id'
        );
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function viewers()
    {
        return $this->belongsToMany(
            'App\Models\Users\User',
            'tickets_viewed',
            'ticket_id',
            'user_id'
        );
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = [
            'id' => (string)$this->id,
            'title' => $this->subject,
            'type' => $this->tix_type,
            'created_date' => $this->created_date,
            'closed_date' => $this->closed_date,
        ];

        return $array;
    }

    public function getAddressAttribute()
    {
        return \App\Helpers\Address::getFullAddress(
            $this->propaddress1, $this->propaddress2, $this->propcity, $this->propstate, $this->propzip
        );
    }

    /**
     * @return null|string
     */
    public function getClientNameAttribute()
    {
        return $this->client ? $this->client->fullname : null;
    }

    /**
     * @return null
     */
    public function getAppraisalTypeNameAttribute()
    {
        return $this->appraisalType ? $this->appraisalType->title : null;
    }

    /**
     * @return null|string
     */
    public function getStatusNameAttribute()
    {
        return $this->statusRel ? $this->statusRel->status->name : config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getClosedByAttribute()
    {
        return $this->closedByUser ? $this->closedByUser->fullname : config('constants.not_available');
    }

    /**
     * @return null|string
     */
    public function getCreatedByAttribute()
    {
        return $this->createdByUser ? $this->createdByUser->fullname : null;
    }

    /**
     * @return null|string
     */
    public function getBorrowerAttribute()
    {
        return $this->apprOrder ? $this->apprOrder->borrower : null;
    }

    /**
     * @return string
     */
    public function getOrderStatusAttribute()
    {
        if ($this->type == config('constants.order_type_alt')) {
            $result = self::join('alt_order', 'alt_order.id', '=', 'tickets.orderid')
                ->leftJoin('alt_order_status', 'alt_order_status.id', '=', 'alt_order.status')
                ->where('tickets.id', $this->id)
                ->select('alt_order_status.name')
                ->get();

            if ($result->name) {
                return $result->name;
            }

        } elseif ($this->type == config('constants.order_type_appraisal') || !$this->type) {
            $result = self::join('appr_order', 'appr_order.id', '=', 'tickets.orderid')
                ->join('order_status', 'order_status.id', '=', 'appr_order.status')
                ->where('tickets.id', $this->id)
                ->select('order_status.descrip')
                ->first();

            if ($result->descrip) {
                return $result->descrip;
            }
        }

        return config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getTicketOrderAddressAttribute()
    {
        if ($this->type == config('constants.order_type_alt')) {
            if ($this->altOrder) {
                return $this->altOrder->address;
            }

        } elseif (($this->type == config('constants.order_type_appraisal') || !$this->type) && $this->apprOrder) {
            return $this->apprOrder->address;
        }

        return config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getClosedFormatDateAttribute()
    {
        return $this->closed_date ? date('m/d/Y g:i A', $this->closed_date) : '--';
    }

    /**
     * @return string
     */
    public function getLockedFormatDateAttribute()
    {
        return $this->locked_date ? date('m/d/Y g:i A', $this->locked_date) : '--';
    }

    /**
     * @return string
     */
    public function getCreatedFormatDateAttribute()
    {
        return $this->created_date ? date('m/d/Y g:i A', $this->created_date) : '--';
    }

    /**
     * @return string
     */
    public function getLockedAttribute()
    {
        return $this->lockedByUser ? $this->lockedByUser->fullname : config('constants.not_available');
    }

    /**
     * @return string
     */
    public function getNormalizeSubjectAttribute()
    {
        return trim('RE: ' . trim(preg_replace('/RE:{1,}/', '', trim($this->subject))));
    }

    /**
     * @return bool
     */
    public function getSendNotificationAttribute()
    {
        if (!$this->assignid || !$this->assigntype) {
            return false;
        } elseif ($this->assigntype == config('constants.assign_type_user') && !$this->assignid) {
            return false;
        } elseif ($this->assignid == admin()->id) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getTeamPhoneAttribute()
    {
        if ($this->apprOrder) {
            return $this->apprOrder->teamPhone;
        }

        return config('app.phone_number');
    }

    // ------------------------

    /**
     * @param $query
     * @param bool|array $groups
     * @return mixed
     */
    public function scopeOfGroups($query, $groups = false)
    {
        if ($groups) {
            return $query->where(function ($query) use ($groups) {
                foreach ($groups as $row) {
                    if ($row == 'mine') {
                        $query->orWhere([
                            ['assigntype', '=', config('constants.assign_type_user')],
                            ['assignid', '=', admin()->id],
                        ]);
                    }

                    if ($row == 'individual') {
                        $query->orWhere([
                            ['assigntype', '=', config('constants.assign_type_user')],
                            ['assignid', '>', 0],
                        ]);
                    }

                    if ($row == 'unassigned') {
                        $query->orWhere('assignid', 0);
                    }

                    if ($row == 'participant') {
                        $query->orWhereHas('participates', function ($query) {
                            $query->participant();
                        });
                    }

                    if (stripos($row, 'user_') !== false) {
                        $userId = str_replace('user_', '', $row);
                        $query->orWhere([
                            ['assigntype', '=', config('constants.assign_type_user')],
                            ['assignid', '=', $userId],
                        ]);
                    } elseif (stripos($row, 'team_') !== false) {
                        $teamId = str_replace('team_', '', $row);
                        $query->orWhere([
                            ['assigntype', '=', config('constants.assign_type_team')],
                            ['assignid', '=', $teamId],
                        ]);
                    }
                }
            });
        }
    }

    /**
     * @param $query
     * @param bool|string $closed
     * @return mixed
     */
    public function scopeOfClosed($query, $closed = false)
    {
        if ($closed == config('constants.ticket_open')) {
            return $query->where('closedid', '=', 0);
        } elseif ($closed == config('constants.ticket_close')
            || $closed == config('constants.ticket_closed')) {
            return $query->where('closedid', '>', 0);
        }
    }

    /**
     * @param $query
     * @param bool|int $priority
     * @return mixed
     */
    public function scopeOfPriority($query, $priority = false)
    {
        if ($priority) {
            return $query->where('priority', $priority);
        }
    }

    /**
     * @param $query
     * @param bool|array $states
     * @return mixed
     */
    public function scopeWithStates($query, $states = false)
    {
        if ($states) {
            return $query->whereHas('apprOrder', function ($query) use ($states) {
                $query->ofStates($states);
            });
        }
    }

    /**
     * @param $query
     * @param bool|array $categories
     * @return mixed
     */
    public function scopeWithCategories($query, $categories = false)
    {
        if ($categories) {
            return $query->whereHas('categoryRel', function ($query) use ($categories) {
                $query->ofCategories($categories);
            });
        }
    }

    /**
     * @param $query
     * @param bool|array $statuses
     * @return mixed
     */
    public function scopeWithStatuses($query, $statuses = false)
    {
        if ($statuses) {
            return $query->whereHas('statusRel', function ($query) use ($statuses) {
                $query->ofStatuses($statuses);
            });
        }
    }

    /**
     * @param $query
     * @param bool|string $search
     * @return mixed
     */
    public function scopeOfSearch($query, $search = false)
    {
        if ($search) {
            return $query->whereHas('apprOrder', function ($query) use ($search) {
                $query->ofBorrower($search);
            })->orWhere('tickets.subject', 'like', '%' . $search . '%')
                ->orWhere('tickets.from_content', 'like', '%' . $search . '%')
                ->orWhere('tickets.to_content', 'like', '%' . $search . '%')
                ->orWhere('tickets.orderid', 'like', '%' . $search)
                ->orWhere('tickets.id', 'like', '%' . $search);
        }
    }

    /**
     * @param $query
     * @param int $from
     * @param int $to
     * @return mixed
     */
    public function scopeOfClosedPeriod($query, $from, $to)
    {
        return $query->whereBetween('closed_date', [$from, $to]);
    }

    // ------------------------

    /**
     * @return array
     */
    public static function getPriorityClasses()
    {
        return [
            self::PRIORITY_LOW => 'label-primary',
            self::PRIORITY_NORMAL => 'label-default',
            self::PRIORITY_MEDIUM => 'label-warning',
            self::PRIORITY_HIGH => 'label-danger',
        ];
    }

    /**
     * @return array
     */
    public static function getPriorityList()
    {
        return [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_MEDIUM => 'Medium',
            self::PRIORITY_HIGH => 'High',
        ];
    }

    /**
     * @param bool $priority
     * @return string
     */
    public function getPriorityTitle($priority = false)
    {
        if (!$priority) {
            $priority = $this->priority;
        }

        $list = self::getPriorityList();

        return $list[$priority] ?? config('constants.not_available');
    }

    /**
     * @param $emails
     * @return string
     */
    public function getAdditionalEmails($emails)
    {
        $emailsText = str_replace([' ', ',', '"', "'"], ['', "\n", '', ''], strtolower($emails));
        $list = [];
        foreach (explode("\n", $emailsText) as $email) {
            if (filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                $email = trim($email);
                $list[$email] = $email;
            }
        }

        return implode("\n", $list);
    }
}
