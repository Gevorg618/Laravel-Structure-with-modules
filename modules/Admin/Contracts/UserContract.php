<?php

namespace Modules\Admin\Contracts;

interface UserContract
{
    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchUsers($request);

    /**
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAssignUsers();

    /**
     * @param array $emails
     * @return array
     */
    public function getUsersByEmail($emails);
}