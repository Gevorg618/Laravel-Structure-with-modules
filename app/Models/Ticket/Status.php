<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class Status extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_status';
    protected $fillable = [
        'name',
        'bgcolor',
        'textcolor'
    ];

    public $timestamps = false;

    public function beforeSave()
    {
        $this->skey = getCode($this->name);
        return parent::beforeSave();
    }

    /**
     * Store ticket status
     *
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $status = Status::findOrNew($request->id);
        $status->fill($request->all());

        $status->save();

        return true;
    }
}
