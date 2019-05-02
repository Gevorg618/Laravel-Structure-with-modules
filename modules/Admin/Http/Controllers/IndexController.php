<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Modules\Admin\Library\AdminNavigation;
use Modules\Admin\Repositories\Index\IndexRepository;

class IndexController extends AdminBaseController
{

    /**
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(IndexRepository $indexRepository)
    {
        $dashboardData = AdminNavigation::getAdminNavigation();
        $announcementsData = $indexRepository->getAnnouncements();
        $calendarData = $indexRepository->getCalendarData();
        $calendarAdminUserList = $indexRepository->getCalendarAdminUserList();
        return view(
            'admin::index.index',
                        compact(
                            'dashboardData',
                            'announcementsData',
                            'calendarData',
                            'calendarAdminUserList'
 
                        )
        );
    }

    /**
     * @param $id
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAnnouncement($id, IndexRepository $indexRepository)
    {
        $announcement = $indexRepository->getAnnouncement($id);
        return response()->json(['html' => $announcement->content, 'row' => $announcement]);
    }

    /**
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEventForm(IndexRepository $indexRepository)
    {
        $calendarAdminUserList = $indexRepository->getCalendarAdminUserList();

        $calendarData = $indexRepository->getCalendarData();
        $view = View::make('admin::index.partials._event_form', compact('calendarData', 'calendarAdminUserList'))->render();
        return response()->json(['html' => $view]);
    }

    /**
     * @param $id
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewEvent($id, IndexRepository $indexRepository)
    {
        $row = $indexRepository->getCalendarEvent($id);
        $users = $indexRepository->getCalendarEventUsersVisible($row->id);
        $userName = getUserFullNameById($row->created_by);
        $view = View::make('admin::index.partials._event_view', compact('row', 'userName', 'users'))->render();
        return response()->json(['html' => $view, 'title' => $row->title]);
    }

    /**
     * @param $id
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function editEventForm($id, IndexRepository $indexRepository)
    {
        $row = $indexRepository->getCalendarEvent($id);
        $calendarData = $indexRepository->getCalendarData();
        $visibleUsers = $indexRepository->getVisibleUsersList($row->id) ?: null;
        $calendarAdminUserList = $indexRepository->getCalendarAdminUserList();
        $view = View::make('admin::index.partials._event_update_form', compact('row', 'calendarData', 'calendarAdminUserList', 'visibleUsers'))->render();
        return response()->json(['html' => $view]);
    }

    /**
     * @param Request $request
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function loadEvents(Request $request, IndexRepository $indexRepository)
    {
        $events = $indexRepository->getCalendarEvents($request->get('start'), $request->get('end'));
        return response()->json($events);
    }

    /**
     * @param Request $request
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function addEvent(Request $request, IndexRepository $indexRepository)
    {
        $addEvent = $indexRepository->addEvent($request->all());
        return response()->json($addEvent);
    }

    /**
     * @param $id
     * @param Request $request
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function editEvent($id, Request $request, IndexRepository $indexRepository)
    {
        $update = $indexRepository->updateEvent($id, $request->all());
        return response()->json($update);
    }

    /**
     * @param $id
     * @param IndexRepository $indexRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteEvent($id, IndexRepository $indexRepository)
    {
        $deleteEventStatus = $indexRepository->deleteEvent($id);
        return response()->json($deleteEventStatus);
    }
}
