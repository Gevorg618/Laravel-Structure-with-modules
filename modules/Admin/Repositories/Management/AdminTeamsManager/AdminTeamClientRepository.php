<?php

namespace Modules\Admin\Repositories\Management\AdminTeamsManager;

use App\Models\Management\AdminTeamsManager\AdminTeamClient;

class AdminTeamClientRepository
{
    /**
     * Object of AdminTeamClient class
     *
     * @var $adminTeamClient
     */
    private $adminTeamClient;

    /**
     * AdminTeamClientRepository constructor.
     */
    public function __construct()
    {
        $this->adminTeamClient = new AdminTeamClient();
    }
    
    public function getAdminClientsByType($typeName)
    {
       $teams =  $this->adminTeamClient->with( [ 'adminTeam' => function($query) use ($typeName) {
                $query->where('team_type', $typeName);
        }])->get();

       return $teams;
    }   
}