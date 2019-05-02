<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Comment extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_comments';

    protected $fillable = [
        'is_public',
        'comment',
        'to_address',
        'ticket_id',
        'created_by',
        'created_date',
        'html_content',
        'additional_addresses',
    ];

    public $timestamps = false;

    protected $dates = ['created_date'];

    protected $appends = ['content'];

    protected $casts = [
      'created_date' => 'datetime'
    ];

    public function getContentAttribute()
    {
        return preg_replace('#(<br */?>\s*)+#i', '<br />', nl2br(strip_tags($this->html_content)));
    }

    /**
     * Connection to user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'created_by');
    }
}
