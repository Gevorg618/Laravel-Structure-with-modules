<?php

namespace Modules\Admin\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Repositories\Accounting\PaymentRepository;
use Modules\Admin\Http\Requests\Accounting\LocatePaymentRequest;

/**
 * Class LocatePaymentsController
 * @package Modules\Admin\Http\Controllers
 */
class LocatePaymentsController extends Controller
{
    /**
     * @var PaymentRepository
     */
    protected $paymentRepo;
    /**
     * @var OrderRepository
     */
    protected $orderRepo;

    /**
     * LocatePaymentsController constructor.
     * @param PaymentRepository $paymentRepo
     * @param OrderRepository $orderRepo
     */
    public function __construct(PaymentRepository $paymentRepo, OrderRepository $orderRepo)
    {
        $this->paymentRepo = $paymentRepo;
        $this->orderRepo = $orderRepo;
    }

    /**
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(LocatePaymentRequest $request)
    {

        $term = trim($request->get('term'));
        $view = view('admin::accounting.locate-payments.index');
        if ($term) {
            $apprChecks = $this->paymentRepo->getApprChecksByTerm($term);
            $orderIds = $apprChecks->pluck('order_id');
            $apprChecksOrders = $this->orderRepo->getOrdersByIds($orderIds)->keyBy('id');

            $alChecks = $this->paymentRepo->getALChecksByTerm($term);
            $orderIds = $alChecks->pluck('order_id');
            $alChecksOrders = $this->orderRepo->getOrdersByIds($orderIds);

            // get credit cards
            $apprCards = $this->paymentRepo->getApprCardsByTerm($term);
            $orderIds = $apprCards->pluck('order_id');
            $apprCardsOrders = $this->orderRepo->getOrdersByIds($orderIds)->keyBy('id');
            $apprFDCards = $this->paymentRepo->getFDApprCardsByTerm($term);
            $orderIds = $apprFDCards->pluck('order_id');
            $apprFDCardsOrders = $this->orderRepo->getOrdersByIds($orderIds)->keyBy('id');
            $alCards = $this->paymentRepo->getALCardsByTerm($term);
            $orderIds = $alCards->pluck('order_id');
            $alCardsOrders = $this->orderRepo->getOrdersByIds($orderIds);

            // Appraiser Checks
            $apprChecksSent = $this->paymentRepo->getApprCheckPaymentsSent($term);
            $orderIds = $apprChecksSent->pluck('orderid');
            $apprChecksSentOrders = $this->orderRepo->getOrdersByIds($orderIds)->keyBy('id');

            // Mercurxy Payments
            $mercuryPayments = $this->paymentRepo->getMercuryTsysPayments($term);
            $orderIds = $mercuryPayments->pluck('lni_id');
            $mercuryPaymentsOrders = $this->orderRepo->getOrdersByIds($orderIds)->keyBy('id');
            $view = $view->with([
                'apprChecks' => $apprChecks,
                'alChecks' => $alChecks,
                'apprCards' => $apprCards,
                'apprFDCards' => $apprFDCards,
                'alCards' => $alCards,
                'apprChecksSent' => $apprChecksSent,
                'mercuryPayments' => $mercuryPayments,
                'apprChecksSentOrders' => $apprChecksSentOrders,
                'apprChecksOrders' => $apprChecksOrders,
                'apprCardsOrders' => $apprCardsOrders,
                'apprFDCardsOrders' => $apprFDCardsOrders,
                'mercuryPaymentsOrders' => $mercuryPaymentsOrders,
                'alChecksOrders' => $alChecksOrders,
                'alCardsOrders' => $alCardsOrders,
                'term' => $term,
            ]);
        }
        return $view;
    }
}
