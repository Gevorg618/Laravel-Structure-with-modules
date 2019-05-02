<?php

namespace App\Models\Integrations\APIUsers;

use App\Models\BaseModel;
use Exception;
use Carbon\Carbon;
use App\Models\Integrations\APIUsers\{ APIEmailSettingContent, APIUserGroup };

class APIUser extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_user';

    protected $fillable = [
            'title',
            'company',
            'day_limit',
            'month_limit',
            'in_production',
            'is_active',
            'is_visible_all',
            'permissions',
            'contact_email_as_additional',
            'created',
            'api_key',
            'updated'
        ];

    public $timestamps = false;

    public static function getAll()
    {
        return self::orderBy('title', 'ASC')->get();
    }

    public static function getById($id)
    {
        return self::where('id', $id)->first();
    }

    public static function saveApiUser($id = null, $inputs)
    {
        try {
            if (is_null($id)) {
                //Api User
                $inputs['info']['created'] = Carbon::now()->timestamp;
                $inputs['info']['api_key'] = md5(Carbon::now()->timestamp);
                $apiUser = self::create($inputs['info']);
                $id = $apiUser->id;
                // //Groups
                if (!is_null($inputs['groups'])) {
                    foreach($inputs['groups'] as $group) {
                        APIUserGroup::create([
                                'api_id' => $id,
                                'group_id' => $group
                            ]);
                    }
                }
                //Email Settings
                foreach($inputs['emailsetting'] as $emailSettingId => $emailSettingContent) {
                    if (!empty($emailSettingContent)) {
                        APIEmailSettingContent::create([
                                'api_user_id' => $id,
                                'api_email_setting_id' => $emailSettingId,
                                'content' => $emailSettingContent
                            ]);
                    }
                }

            } else {
                //Api User
                $inputs['info']['updated'] = Carbon::now()->timestamp;
                self::where('id', $id)->update($inputs['info']);


                // //Groups
                APIUserGroup::where('api_id', $id)->delete();
                if (!is_null($inputs['groups'])) {
                    foreach($inputs['groups'] as $group) {
                        APIUserGroup::create([
                                'api_id' => $id,
                                'group_id' => $group
                            ]);
                    }
                }
                //Email Settings
                foreach($inputs['emailsetting'] as $emailSettingId => $emailSettingContent) {
                    if (!empty($emailSettingContent)) {
                        APIEmailSettingContent::updateOrCreate(
                            [
                                'api_user_id' => $id,
                                'api_email_setting_id' => $emailSettingId
                            ],
                            [
                                'content' => $emailSettingContent
                            ]);
                    }
                }
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany('App\Models\Clients\Client');
    }


}
