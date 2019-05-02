<?php

namespace App\Models\Integrations\APIUsers;

use App\Models\BaseModel;
use Carbon\Carbon;

class APILog extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api_log';

    public static function getAll($id)
    {
        return self::select('id', 'code', 'created', 'is_success', 'request')->where('api_id', $id)->orderBy('created', 'DESC')->paginate(200);
    }

    public static function getContent($id)
    {
        return self::select('log')->where('id', $id)->first();
    }

    public static function getCount($id)
    {
        return self::where('api_id', $id)->count();
    }

    public static function search($inputs)
    {
        $logs = self::where('api_id', $inputs['id'])

            ->when(!empty($inputs['term']), function ($query) use ($inputs) {

                $inputs['term'] = trim($inputs['term']);
                return $query->where('request' , 'LIKE' , '%'.$inputs['term'].'%');

            })->when (!empty($inputs['from']), function ($query) use ($inputs) {

                return $query->where('created', '>', Carbon::parse($inputs['from'])->startOfDay()->timestamp);

            })->when (!empty($inputs['to']), function ($query) use ($inputs) {

                return $query->where('created', '<', Carbon::parse($inputs['to'])->endOfDay()->timestamp);

            })->orderBy('created', 'DESC')->paginate(!empty($inputs['per_page']) ? $inputs['per_page'] : 200);

       return $logs;
    }
}
