<?php

namespace Modules\Admin\Repositories\Management;

use App\Models\Appraiser\AppraiserGroup;
use Yajra\DataTables\Facades\DataTables;

class AppraiserGroupsRepository
{
    private $appraiserGroup;

    /**
     * AppraiserGroupsRepository constructor.
     *
     */
    public function __construct()
    {
        $this->appraiserGroup = new AppraiserGroup();
    }

    /**
     * get appraiser groups for dataTable
     *
     * @return array $groupsDataTables
     */
    public function appraiserGroupsDataTables()
    {
        $groups = $this->appraiserGroup->withCount('users')->with('manager', 'createdBy')->get();

        foreach ($groups as $group) {
            if ($group->createdBy->userData) {
                $group->creator = $group->createdBy->userData->firstname." ".$group->createdBy->userData->lastname;
            } else {
                $group->creator = $group->createdBy->email;
            }
            if ($group->manager->userData) {
                $group->managerid = $group->manager->userData->firstname." ".$group->manager->userData->lastname;
            } else {
                $group->managerid = $group->manager->email;
            }
            $group->created_date = $group->created_at;
        }
        $groupsDataTables = Datatables::of($groups)
            ->editColumn('options', function ($group) {
                return  '<div class = "btn-group">
                               <button type = "button" class = "btn btn-success dropdown-toggle btn-xs" data-toggle = "dropdown">
                                  Actions
                                  <span class = "caret"></span>
                               </button>
                               <ul class = "dropdown-menu" role = "menu">
                                  <li>
                                    <a href = "'.route('admin.management.appraiser.edit', $group->id).'">
                                    <span class="glyphicon glyphicon-pencil"></span> 
                                        Edit
                                    </a>
                                  </li>
                               </ul>                               
                            </div>';
            })
            ->rawColumns(['options'])
            ->make(true);
        return $groupsDataTables;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getOne($id)
    {
        return $this->appraiserGroup->find($id);
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function getOneWithManager($id)
    {
        return $this->appraiserGroup->where('id', $id)->with('manager', 'appraisers')->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getAppraiserGroupByUserId($id) {
        return AppraiserGroup::whereHas('users', function ($query) use ($id) {
            return $query->where('user.id', $id);
        })->first();
    }
}