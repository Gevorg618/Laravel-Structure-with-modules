<?php

namespace Modules\Admin\Http\Controllers\Statistics;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Session;
use App\Models\Clients\Client;
use App\Models\Customizations\Type;
use App\Models\Customizations\DelayCode;
use Modules\Admin\Repositories\Statistics\AvgDelayCodesRepository;
use Modules\Admin\Http\Requests\Statistics\AvgCodesDelayRequest;

class AvgDelayCodesController extends AdminBaseController
{
    /**
     * index page
     * @param Type, AvgDelayCodesRepository
     * @return View
     */
    public function index(Type $type, AvgDelayCodesRepository $avgDelayCodesRepository)
    {
        $clients = Client::getAllClients();
        $types = $type->allTypes();
        $rows = $avgDelayCodesRepository->getOrderDelayCodeTypes();
        $submit = false;

        foreach ($rows as $row) {
            $row->average = $avgDelayCodesRepository->getAverageDelayCodeDaysByType($row->id);
            $row->count = "";
        }
        return view('admin::statistics.avg-delay-codes.index',
            compact(
                'clients',
                'types',
                'rows',
                'submit'
            )
        );
    }

    /**
     * submit
     * @param AvgCodesDelayRequest, AvgDelayCodesRepository, AvgDelayCodesRepository
     * @return View
     */
    public function submit(
            AvgCodesDelayRequest $request,
            Type $type,
            AvgDelayCodesRepository $avgDelayCodesRepository
        )
    {
        $submit = true;
        $from = explode(" ", $request->daterange)[0];
        $to = explode(" ", $request->daterange)[2];
        $datetype = $request->datetype;
        $client = $request->client;
        $apprTypes = $request->apprtypes;

        $clients = Client::getAllClients();
        $types = $type->allTypes();
        $rows = $avgDelayCodesRepository->getOrderDelayCodeTypes();

        foreach ($rows as $row) {
            $row->orders = $avgDelayCodesRepository->getAverageDelayCodeOrdersDaysByTypeDate($row->id, $from, $to, $datetype, $client, $apprTypes);
            $row->average = $avgDelayCodesRepository->getAverageDelayCodeDaysByTypeDate($row->id, $from, $to, $datetype, $client, $apprTypes);
            $row->total = count($row->orders);
        }

        return view('admin::statistics.avg-delay-codes.index',
            compact(
                'clients',
                'types',
                'rows',
                'submit'
            )
        );
    }
}
