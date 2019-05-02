<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use App\Models\Appraisal\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Admin\Http\Requests\Accounting\Receivable\GetDataRequest;
use Modules\Admin\Http\Requests\Accounting\Receivable\ViewClientsReportRequest;
use Modules\Admin\Http\Requests\Accounting\Receivable\ViewClientsRequest;
use Modules\Admin\Services\Accounting\ReceivableReportService;

class ReceivableReportController extends Controller
{
    protected $service;

    /**
     * ReceivableReportController constructor.
     * @param $service
     */
    public function __construct(ReceivableReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('admin::accounting.receivable-reports.index', [
            'filters' => $this->service->getFilters(),
            'credits' => $this->service->getCredits(),
        ]);
    }

    /**
     * @param GetDataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInvoiced(GetDataRequest $request)
    {
        $filter = $request->post('filter');
        $credits = $request->post('credits');
        list($invoiced, $paginator) = $this->service->getInvoiced($filter, $credits);
        return response()->json([
            'html' => view('admin::accounting.receivable-reports.clients_list', [
                'rows' => $invoiced['invoiced']['rows'],
                'counts' => $invoiced['invoiced']['counts'],
                'title' => 'Invoiced Totals',
                'paginator' => $paginator
            ])->render()
        ]);
    }

    /**
     * @param GetDataRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNonInvoiced(GetDataRequest $request)
    {
        $filter = $request->post('filter');
        $credits = $request->post('credits');
        list($noninvoiced, $paginator) = $this->service->getNonInvoiced($filter, $credits);
        return response()->json([
            'html' => view('admin::accounting.receivable-reports.clients_list', [
                'rows' => $noninvoiced['noninvoiced']['rows'],
                'counts' => $noninvoiced['noninvoiced']['counts'],
                'title' => 'Non Invoiced Totals',
                'paginator' => $paginator
            ])->render()
        ]);
    }

    /**
     * @param ViewClientsRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewClients(ViewClientsRequest $request)
    {
        $clients = json_decode($request->get('ids'));
        $filters = [];
        $filter = $request->get('filter');
        if($filter) {
            $filters['days-group'] = [$filter];
        }
        $credits = $request->get('credits');
        $filters['credits'] = $credits;
        $rows = $this->service->accountingViewClientsGetInfo($clients, $filters);
        return view('admin::accounting.receivable-reports.view_clients', [
            'rows' => $rows,
            'filter' => $filter,
            'credits' => $credits,
        ]);
    }

    /**
     * @param ViewClientsReportRequest $request
     * @return Response
     */
    public function viewClientsReport(ViewClientsReportRequest $request)
    {
        $clients = $request->get('clients');
        $ignore = $request->get('ignore');

        // Download invoices
        if($request->has('printinvoices')) {
            // Make sure we have ids
            $orders = Order::whereIn('id', $ignore)->get();

            $pdf = \PDF::loadView('invoice', [
                'orders' => $orders
            ]);
            return $pdf->download('invoice.pdf');
        }

        if($request->has('printlargelabels')) {
            $clients = explode(',', $request->post('clients'));
            // Create labels for all clients
            $labels = $this->service->getPDFClientLabels($clients);
            $pdf = \PDF::loadView('labels', [
                'labels' => $labels
            ]);
            return $pdf->download('labels.pdf');
        }

        $filters = ['ignore' => $ignore];

        if($request->has('filter')) {
            $filters['days-group'] = [$request->get('filter')];
        }

        $filters['credits'] = $request->get('credits');

        // Load client data
        $rows = $this->service->accountingViewClientsGetInfo(explode(',', $clients), $filters);

        $pdf = \PDF::loadView('receivables_pdf', [
            'rows' => $rows,
        ])->setPaper('a4', 'landscape');
        return $pdf->download('receivables.pdf');
    }
}
