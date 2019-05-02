<?php

namespace Modules\Admin\Services\Ticket;

use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\Management\AdminTeamsManager\AdminTeamMember;
use App\Models\Management\GroupPermission;
use Modules\Admin\Repositories\Users\UserRepository;

class AssignmentService
{
    protected $user;

    /**
     * AssignmentService constructor.
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->user = $userRepo;
    }

    /**
     * @param string $term
     * @return array
     */
    public function getMentions($term)
    {
        $typed = str_replace('@', '', $term);

        $users = [];
        $assignments = $this->getAssignmentList();
        foreach ($assignments['users'] as $userId => $userName) {
            if (stripos($userName, $typed) !== false) {
                $userId = str_replace('user_', '', $userId);
                $users[$userId] = $userName;
            }
        }

        return $users;
    }

    /**
     * @return array
     */
    public function getAssignmentList()
    {
        $rows = AdminTeam::where('is_active', 1)->orderBy('team_title', 'asc')->get();

        $teams = [];
        if ($rows) {
            foreach ($rows as $row) {
                $teams['team_' . $row->id] = $row->team_title;
            }
        }

        $rows = $this->user->getAssignUsers();

        $users = [];
        foreach ($rows as $user) {
            $users['user_' . $user->id] = $user->fullname;
        }

        return ['teams' => $teams, 'users' => $users];
    }

    /**
     * @return array
     */
    public function getClientSideAssignmentList()
    {
        $list = $this->getAssignmentList();
        $items = [];
        $items[] = [
            'text' => 'Unset',
            'children' => ['r' => '-- None --']
        ];

        $items[] = [
            'text' => 'Me',
            'children' => ['user_' . admin()->id => admin()->fullname]
        ];

        foreach ($list as $key => $rows) {
            // Unset my user id
            if (isset($rows['user_' . admin()->id])) {
                unset($rows['user_' . admin()->id]);
            }
            $items[] = ['text' => ucwords($key), 'children' => $rows];
        }
        return $items;
    }

    /**
     * @param array $list
     * @param int $id
     * @return string|null
     */
    public function getAssignUserName($list, $id)
    {
        if (strpos($id, 'team_') !== false) {
            if (isset($list['teams'][$id])) {
                return $list['teams'][$id];
            }
        } elseif (strpos($id, 'user_') !== false) {
            if (isset($list['users'][$id])) {
                return $list['users'][$id];
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getPipelineList()
    {
        $list = $this->getAssignmentList();

        $permission = GroupPermission::where([
            ['group_id', '=', admin()->admin_group],
            ['perm_key', '=', 'can_view_all_team_inquiries'],
            ['value', '=', 1]
        ])->count();

        if ($permission) {
            $rows = AdminTeam::orderBy('team_title')->orderBy('team_type')->get();
        } else {
            $rows = AdminTeam::whereIn('id', function ($query) {
                $query->select('team_id')
                    ->from(with(new AdminTeamMember())->getTable())
                    ->where('user_id', admin()->id);
            })
                ->orderBy('team_title')
                ->orderBy('team_type')
                ->get();
        }

        if ($rows->count()) {
            $teams = $rows->pluck('team_title', 'id');

            foreach ($list['teams'] as $teamId => $teamTitle) {
                $id = str_replace('team_', '', $teamId);

                if (!isset($teams[$id])) {
                    unset($list['teams'][$teamId]);
                }
            }
        }

        return [
            'mine' => 'My Tickets',
            'participant' => 'Participating Tickets',
            'individual' => 'Individual Tickets',
            'unassigned' => 'Unassigned Tickets',
            'teams' => $list['teams'],
            'users' => $list['users'],
        ];
    }
}
