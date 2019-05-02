<?php


namespace Modules\Admin\Repositories;


use App\Models\Management\ASCLicense;
use App\Models\User;

class AscDataRepository
{
    /**
     * @param User $user
     * @param array $licensesList
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAscLicenses(User $user, $licensesList = [])
    {
        return ASCLicense::whereRaw(
            "(LOWER(SUBSTRING(zip, 1, 5))) = '" . strtolower(substr($user->comp_zip, 0, 5)) . "'"
        )->where('fname', $user->firstname)
            ->where('lname', $user->lastname)
            ->whereIn('lic_number', $licensesList)
            ->get();
    }

    /**
     * @param User $user
     * @param $number
     * @return \Illuminate\Database\Eloquent\Model|null|static
     */
    public function getLicenseByNumberAndNames(User $user, $number)
    {
        return ASCLicense::where('number', $number)
            ->where('fname', $user->firstname)
            ->where('lname', $user->lastname)->first();
    }
}