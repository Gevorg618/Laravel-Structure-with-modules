<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Accounting\APCalendarRequest;
use Modules\Admin\Repositories\CalendarRepository;

class APCalendarController extends Controller
{
    protected $calendarRepo;

    /**
     * APCalendarController constructor.
     * @param $orderRepo
     */
    public function __construct(CalendarRepository $calendarRepository)
    {
        $this->calendarRepo = $calendarRepository;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::ap_calendar.index');
    }

    /**
     * @param APCalendarRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function load(APCalendarRequest $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        $orders = $this->calendarRepo->getData($start, $end);
        $events = [];

        foreach ($orders as $event) {
            for ($i = 1; $i <= 4; $i++) {

                $amount = $event->{'total' . $i};

                $events[] = [
                    'url' => '#',
                    'date' => $event->fdate,
                    'title' => $this->calendarRepo->getTitleByRange($i, $amount),
                    'start' => $event->fdate,
                    'end' => $event->fdate,
                    'color' => $this->calendarRepo->getColorByRange($i),
                    'textColor' => 'white',
                ];
            }
        }

        return response()->json($events);
    }
}
