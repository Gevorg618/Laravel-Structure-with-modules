<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Appraisal\Cert;

class CertRepository
{
    /**
     * @param $id
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function findByIdAndUser($id, $userId)
    {
        return Cert::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }
}