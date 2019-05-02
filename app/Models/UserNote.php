<?php

namespace App\Models;


class UserNote extends BaseModel
{
    protected $table = 'user_notes';

    public $timestamps = false;

    /**
     * Relation to User
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'adminid');
    }

    /**
     * Relation of user-last editor
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lastEditor()
    {
        return $this->belongsTo(User::class, 'last_edited_by');
    }

    /**
     * @return string
     */
    public function getUserFullNameAttribute()
    {
        if ($this->user) {
            return $this->user->firstname . ' ' . $this->user->lastname;
        }
        return 'N/A';
    }

    /**
     * @return string
     */
    public function getLastEditorFullNameAttribute()
    {
        if ($this->lastEditor) {
            return $this->lastEditor->firstname . ' ' . $this->lastEditor->lastname;
        }
        return 'N/A';
    }
}
