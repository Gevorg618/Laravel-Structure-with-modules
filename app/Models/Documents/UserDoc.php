<?php

namespace App\Models\Documents;

use App\Models\BaseModel;

class UserDoc extends BaseModel
{
    protected $table = 'user_docs';

    const TYPE_BACKGROUND_CHECK = 'backgroundcheck';

    public $timestamps = false;

    protected function getPath()
    {
        return sprintf('user_documents/%s', $this->userid);
    }

    public function getFullPathAttribute()
    {
        return sprintf('%s/%s', $this->getPath(), $this->filename);
    }

    /**
     * @param $query
     * @param string $type
     * @return mixed
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * @param $query
     * @param string $name
     * @return mixed
     */
    public function scopeOfName($query, $name)
    {
        return $query->where('name', 'like', '%' . $name . '%');
    }
}
