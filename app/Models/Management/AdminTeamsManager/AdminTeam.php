<?php

namespace App\Models\Management\AdminTeamsManager;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Management\AdminTeamsManager\{ AdminTeamStatusSelectStaff, AdminTeamStatusSelectStatus, AdminTeamStatusSelectFlag, AdminTeamStatusSelectLoanType, AdminTeamClient, AdminTeamMember, AdminTeamStates };


class AdminTeam extends BaseModel
{
    use SoftDeletes;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admin_teams';

    protected $fillable = [
        'team_title',
        'team_key',
        'supervisor',
        'team_type',
        'is_active',
        'team_phone',
        'team_cap',
        'descrip',
        'status_select_sort',
        'qc_uw_pipeline_color',
        'is_in_status_select',
    ];

    public $timestamps = false;

    protected $dates = ['deleted_at'];

    public static function allAdminTeams()
    {
        return self::where('is_active', 1)->orderBy('team_title', 'ASC')->get();
    }

    public static function getAdminTeamById($id)
    {
        return self::where('id', $id)->first();
    }

    public static function getAdminTeams()
    {
        return self::select('id', 'team_title')->orderBy('team_title')->get();
    }

    /**
     * Return list of available admin team types
     * @return array
     */
    public static function getAdminTeamTypes() {
        return self::$teamTypes;
    }

    public static $teamTypes = [
        'appr' => 'Appraisal',
        'al' => 'Alternative Valuation',
        'inq' => 'Inquiries',
        'status' => 'Status Select',
    ];

    /**
     *Save Admin Teams
     * @param $id, $data, $relations
     * @return bool
     */
    public static function saveAdminTeam($id=null, $data, $relations) {
        try {
            if(is_null($id)) {
                $adminTeam = self::create($data);
                $id = $adminTeam->id;
            } else {
                self::where('id', $id)->update($data);
            }

            // Delete
            if(!is_null($id)) {
                self::deleteById($id);
            }

            // Members
            foreach($relations as $relationId => $relation) {
                switch($relationId) {
                    case 'members':
                        foreach($relation as $i) {
                            AdminTeamMember::create(['team_id' => $id, 'user_id' => $i]);
                        }
                    break;

                    case 'clients':
                        foreach($relation as $i) {
                            AdminTeamClient::create(['team_id' => $id, 'user_group_id' => $i]);
                        }
                    break;

                    case 'states':
                        foreach($relation as $i) {
                            AdminTeamStates::create(['team_id' => $id, 'state' => $i]);
                        }
                    break;

                    case 'staff':
                        foreach($relation as $i) {
                            AdminTeamStatusSelectStaff::create(['team_id' => $id, 'user_id' => $i]);
                        }
                    break;

                    case 'statuses':
                        foreach($relation as $i) {
                            AdminTeamStatusSelectStatus::create(['team_id' => $id, 'status_id' => $i]);
                        }
                    break;

                    case 'flags':
                        foreach($relation as $i) {
                            AdminTeamStatusSelectFlag::create(['team_id' => $id, 'flag_key' => $i]);
                        }
                    break;

                    case 'loantypes':
                        foreach($relation as $i) {
                            AdminTeamStatusSelectLoanType::create(['team_id' => $id, 'loan_id' => $i]);
                        }
                    break;
                }
            }

        } catch(Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Delete Saved Data
     *
     * @param $id
     * @return void
     */
    public static function deleteById($id)
    {
        AdminTeamMember::where('team_id', $id)->delete();
        AdminTeamClient::where('team_id', $id)->delete();
        AdminTeamStates::where('team_id', $id)->delete();
        AdminTeamStatusSelectStaff::where('team_id', $id)->delete();
        AdminTeamStatusSelectStatus::where('team_id', $id)->delete();
        AdminTeamStatusSelectFlag::where('team_id', $id)->delete();
        AdminTeamStatusSelectLoanType::where('team_id', $id)->delete();
    }

    /**
     *
     * @return collection
     */
    public function teamMemberUsers() {

        return $this->belongsToMany('App\Models\Users\User', 'admin_team_member', 'team_id');
    }

    /**
     *
     * @return collection
     */
    public function apprDailyStats() {

        return $this->hasMany('App\Models\Appraisal\ApprDashboardDailyStats', 'team_id');
    }

}
