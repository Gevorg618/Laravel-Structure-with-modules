<?php

namespace Modules\Admin\Repositories\Appraisal;


use App\Models\Management\FHALicense;
use App\Models\User;

class FHALisencesRepository
{
    /**
     * @param User $user
     * @param array $currentLicencesList
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getLisencesForUserDocs(User $user, $currentLicencesList = [])
    {
        $rows = FHALicense::whereRaw(
            "LOWER(SUBSTRING(zip, 1, 5)) = '" .strtolower(substr($user->comp_zip, 0, 5))."'"
        )->where('firstname', $user->firstname)
            ->where('lastname', $user->lastname)
            ->orWhereIn('license_number', $currentLicencesList)->get();
        if(!$rows) {
            $rows = FHALicense::where('state', $user->comp_state)
                ->where('firstname', $user->firstname)
                ->where('lastname', $user->lastname)
                ->orWhereIn('license_number', $currentLicencesList)->get();
        }

        return $rows;
    }

    /**
     * @param array $numbers
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getLicensesByLicenseNumbers($numbers = [])
    {
        return FHALicense::whereIn('license_number', $numbers)->get();
    }
}