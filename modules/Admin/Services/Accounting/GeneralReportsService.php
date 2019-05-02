<?php

namespace Modules\Admin\Services\Accounting;


use Modules\Admin\Repositories\Accounting\AltOrderRepository;
use Modules\Admin\Repositories\Accounting\DocuvaultOrderRepository;
use Modules\Admin\Repositories\Ticket\OrderRepository;

/**
 * Class GeneralReportsService
 * @package Modules\Admin\Services
 */
class GeneralReportsService
{
    protected $orderRepo;
    protected $altOrderRepo;
    protected $docuvaultOrderRepo;
    const ALT = 'alt';
    const DOCUVAULT_EXTERNAL = 'docuvault_external';
    const DOCUVAULT_APPRAISAL = 'docuvault_appraisal';

    /**
     * GeneralReportsService constructor.
     * @param OrderRepository $orderRepo
     * @param AltOrderRepository $altOrderRepo
     * @param DocuvaultOrderRepository $docuvaultOrderRepo
     */
    public function __construct(
        OrderRepository $orderRepo,
        AltOrderRepository $altOrderRepo,
        DocuvaultOrderRepository $docuvaultOrderRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->altOrderRepo = $altOrderRepo;
        $this->docuvaultOrderRepo = $docuvaultOrderRepo;
    }

    /**
     * @param $type
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getData($type)
    {
        switch ($type) {
            case self::ALT:
                return $this->altOrderRepo->getAltMonthlyReport();

            case self::DOCUVAULT_EXTERNAL:
                return $this->docuvaultOrderRepo->getDocuvaultExternalMonthlyReport();

            case self::DOCUVAULT_APPRAISAL:
                return $this->orderRepo->getDocuvaultAppraisalMonthlyReport();
        }
    }

    /**
     * @return array
     */
    public function getGeneralReportList() {
        return [
            self::ALT => 'Alternative Valuation Revenue',
            self::DOCUVAULT_EXTERNAL => 'DocuVault External',
            self::DOCUVAULT_APPRAISAL => 'DocuVault Appraisal',
        ];
    }
}