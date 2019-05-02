<?php

namespace App\Models\Documents;

use App\Models\BaseModel;

class RemoteFile extends BaseModel
{
    protected $table = 'remote_files';
    protected $fillable = ['name', 'path', 'bucket', 'created_by', 'is_public', 'updated_by'];

    public $timestamps = true;

    public function user()
    {
        return $this->belongsTo('App\Models\Users\User', 'created_by');
    }

    public function getFilenameAttribute()
    {
        return $this->path . '/' . $this->name;
    }
}
