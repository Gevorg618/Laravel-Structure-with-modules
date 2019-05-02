<?php

namespace Modules\Admin\Repositories;


use App\Models\Documents\UserDoc;

class UserDocsRepository
{
    const BACKGROUND_CHECK = 'backgroundcheck';
    const STATE_LICENSE = 'state_license';
    const INS = 'ins';
    const AGENT_LICENSE = 'agent_license';
    const W9 = 'w9';
    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserInsDocument($userId) {
        return UserDoc::where('userid', $userId)
            ->where('type', self::INS)->latest('created_date')->first();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserBackgroundCheckDocument($userId) {
        return UserDoc::where('userid', $userId)
            ->where('type', self::BACKGROUND_CHECK)
            ->latest('created_date')->first();
    }

    /**
     * @param $userId
     * @param $state
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserStateDocument($userId, $state)
    {
        $likeName = "%".sprintf('%s_%s_cert_doc', $userId, $state)."%";
        return UserDoc::where('userid', $userId)
            ->where('type', self::STATE_LICENSE)
            ->where('name', 'like', $likeName)
            ->latest('created_date')->first();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserLicenseDocument($userId) {
        return UserDoc::where('userid', $userId)
            ->where('type', self::AGENT_LICENSE)
            ->latest('created_date')->first();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function getUserW9Document($userId)
    {
        return UserDoc::where('userid', $userId)
            ->where('type', self::W9)
            ->latest('created_date')->first();
    }

    /**
     * @param $userId
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getUserDocuments($userId)
    {
        return UserDoc::where('userid', $userId)
            ->orderBy('type')->latest('created_date')->get();
    }
}