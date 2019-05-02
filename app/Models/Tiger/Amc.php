<?php

namespace App\Models\Tiger;

use App\Models\BaseModel;

class Amc extends BaseModel
{

    /**
     * Database Connection
     * @var string
     */
    protected $connection = 'tiger';

    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'amc';

    protected $fillable = [
        'title',
        'company_name',
        'is_active',
        'company_address',
        'company_address2',
        'company_city',
        'company_state',
        'company_zip',
        'company_phone',
        'incoming_email',
        'outgoing_email',
        'twitter',
        'linkedin',
        'facebook',
        'instructions'
    ];

    public $timestamps = false;

    /**
     * Connection to AMC user associations
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function associations()
    {
        return $this->hasMany('App\Models\Tiger\AmcUserAssociation');
    }


    /**
     * Save AMC data
     *
     * @param $request
     * @return bool
     */
    public function store($request)
    {
        $amc = Amc::findOrNew($request->id);
        $amc->fill($request->all());

        $amc->setAttribute('is_active', $request->has('is_active'));

        $amc->save();

        if (!empty($request->emails)) {
            $this->saveAssociations($request, $amc);
        }

        return true;
    }

    /**
     * Prepare user associations for saving
     *
     * @param $request
     * @param $amc
     */
    private function saveAssociations($request, $amc)
    {
        $amc->associations()->delete();
        $associations = [];
        $emails = preg_split('/\\r\\n/', $request->emails);

        foreach ($emails AS $email) {
            $associations[] = new AmcUserAssociation(['email' => $email]);
        }

        $amc->associations()->saveMany($associations);
    }

}
