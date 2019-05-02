<?php

namespace Modules\Admin\Repositories\ManagerReport;

use App\Models\Tools\Setting;
use App\Models\Clients\UserGroupNote;
use Yajra\DataTables\Datatables;

class ClientSettingsRepository
{   
    
    /**
     * get Report Types
     * @return array
     */
    public  function getReportTypes() 
    {
        return [
          'client_notes' => [
            'title' => 'Client Notes',
            'description' => '',
            'method' => 'getClientNotes',
            'multiArray' => true,
            'headers' => ['ID', 'Group Title', 'Team Title', 'Created By', 'Created Date', 'Note'],
          ],
        ];
    }

    /**
     * get Report Headers
     * @return array
     */
    protected  function getReportHeaders($type) 
    {
        $types = $this->getReportTypes();
        return isset($types[$type]) ? $types[$type]['headers'] : [];
    }

    /**
     *  get report list
     * @return array
     */
    public  function getReportList() 
    {
        $items = $this->getReportTypes();
        $list = [];
        foreach($items as $k => $info) {
          $list[$k] = $info['title'];
        }

        return $list;
    }

    /**
     *
     * @return array $reportListDatatable
     */
    public function reportListDatatable($clientsId, $dateRange, $dateType, $reportType)
    {

        $dateRange = explode("-", $dateRange);
        
        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));
        
        $userGroupNotes = UserGroupNote::where('dts', '>=' , $dateFrom)->where('dts' , '<=', $dateTo);

        if ($clientsId == "") { 
            $userGroupNotes->whereIn('groupid', []);
        } else {
            $userGroupNotes->whereIn('groupid', $clientsId);
        }
        
        $userGroupNotesRawCount = $userGroupNotes->count();

        return  Datatables::of($userGroupNotes)
                ->editColumn('client', function ($userGroupNote) {
                    return $userGroupNote->group->descrip;
                })
                ->editColumn('team', function ($userGroupNote) {
                    return $userGroupNote->group->adminTeamClient->adminTeam->descrip;
                })
                ->editColumn('created_by', function ($userGroupNote) {
                    return $userGroupNote->user->userData->firstname.' '. $userGroupNote->user->userData->lastname;
                })
                ->editColumn('created_date', function ($userGroupNote) {
                        return $userGroupNote->dts;
                })
                ->editColumn('note', function ($userGroupNote) {
                        return $userGroupNote->notes;
                })
                ->setTotalRecords($userGroupNotesRawCount)
                ->make(true);            
    }

    /**
     * bulid csv to for download 
     * 
     * @return array $reportListDatatable
     */
    public  function buildCSVDocument($clientsId, $dateRange, $dateType, $reportType) 
    {
        
        $headers = $this->getReportHeaders('client_notes');

        $dateRange = explode("-", $dateRange);
        
        // Init
        $dateFrom = date('Y-m-d', strtotime($dateRange[0]));
        $dateTo = date('Y-m-d', strtotime($dateRange[1]));
        
        $userGroupNotes = UserGroupNote::where('dts', '>=' , $dateFrom)->where('dts' , '<=', $dateTo);
        
        if ($clientsId) { 
            $userGroupNotes->whereIn('groupid', $clientsId);
        } else {
            $userGroupNotes->whereIn('groupid', []);
        }

        $userGroupNotes = $userGroupNotes->get();
        $csvArrayPush = [];

        foreach($userGroupNotes as $key => $userGroupNote) {
            
            $csvArrayPush[$key] = [
                'ID' => $userGroupNote->id,
                'Group Title' => $userGroupNote->group->descrip,
                'Team Title' => $userGroupNote->group->adminTeamClient->adminTeam->descrip,
                'Created By' => $userGroupNote->user->userData->firstname.' '. $userGroupNote->user->userData->lastname,
                'Created Date' => $userGroupNote->dts,
                'Note' => $userGroupNote->notes
            ];
                        
        }
        return $csvArrayPush;
    }     

}    