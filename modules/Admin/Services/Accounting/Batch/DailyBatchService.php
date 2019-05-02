<?php

namespace Modules\Admin\Services\Accounting\Batch;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Admin\Repositories\Accounting\ApprAccountingAdminRepository;
use Modules\Admin\Repositories\Appraisal\AlCimCheckPaymentRepository;
use Modules\Admin\Repositories\Appraisal\AlCimPaymentRepository;
use Modules\Admin\Repositories\Appraisal\CimPaymentRepository;
use Modules\Admin\Repositories\Appraisal\FdPaymentRepository;
use Modules\Admin\Repositories\Accounting\CimCheckPaymentRepository;
use Modules\Admin\Repositories\Accounting\DocuvaultCheckPaymentRepository;
use Modules\Admin\Repositories\Accounting\MercuryChargeRepository;
use Yajra\DataTables\Datatables;

/**
 * Class DailyBatchService
 * @package Modules\Admin\Services
 */
class DailyBatchService
{
    protected $cimPaymentRepo;
    protected $fdPaymentRepo;
    protected $cimCheckPaymentRepo;
    protected $mercuryChargeRepo;
    protected $alCimPaymentRepo;
    protected $alCimCheckPaymentRepo;
    protected $docuvaultCheckPaymentRepo;
    protected $apprAccountingAdminRepo;

    protected $exportApprCards = [];
    protected $exportApprChecks = [];
    protected $exportMercury = [];
    protected $exportAltCreditCards = [];
    protected $exportAltChecks = [];
    protected $exportDocuvaultChecks = [];
    protected $exportAdjustments = [];

    /**
     * DailyBatchService constructor.
     * @param $cimPaymentRepo
     */
    public function __construct(
        CimPaymentRepository $cimPaymentRepo,
        FdPaymentRepository $fdPaymentRepo,
        CimCheckPaymentRepository $cimCheckPaymentRepo,
        MercuryChargeRepository $mercuryChargeRepo,
        AlCimPaymentRepository $alCimPaymentRepo,
        AlCimCheckPaymentRepository $alCimCheckPaymentRepo,
        DocuvaultCheckPaymentRepository $docuvaultCheckPaymentRepo,
        ApprAccountingAdminRepository $apprAccountingAdminRepo
    )
    {
        $this->cimPaymentRepo = $cimPaymentRepo;
        $this->fdPaymentRepo = $fdPaymentRepo;
        $this->cimCheckPaymentRepo = $cimCheckPaymentRepo;
        $this->mercuryChargeRepo = $mercuryChargeRepo;
        $this->alCimPaymentRepo = $alCimPaymentRepo;
        $this->alCimCheckPaymentRepo = $alCimCheckPaymentRepo;
        $this->docuvaultCheckPaymentRepo = $docuvaultCheckPaymentRepo;
        $this->apprAccountingAdminRepo = $apprAccountingAdminRepo;
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getAppraisalCards($from, $to, $type)
    {

        $authorizeNet = $this->cimPaymentRepo->getDailyBatchData($from, $to, $type);
        $firstData = $this->fdPaymentRepo->getDailyBatchData($from, $to, $type);

        return $authorizeNet->unionAll($firstData);
    }

    /**
     * get apprasial cards for dataTable
     *
     * @return array $appraisalCardsDataTables
     */
    public function appraisalCardsDataTables($appCards)
    {
       
        return Datatables::of($appCards)
                ->editColumn('gateway', function ($appCard) {
                    return $appCard->gateway;
                })
                ->editColumn('ref_type', function ($appCard) {
                    return $appCard->ref_type ? ucwords(strtolower($appCard->ref_type)) : 'N\A' ;
                })
                ->editColumn('created_date', function ($appCard) {
                    return date('m/d/Y g:i A', $appCard->created_date);
                })
                ->editColumn('order_id', function ($appCard) {
                    return $appCard->order_id;
                })
                ->editColumn('client', function ($appCard) {
                    return $appCard->order->groupData->descrip;
                })
                ->editColumn('team', function ($appCard) {
                    return $appCard->order->adminTeamClient->adminTeam->descrip;
                })
                ->editColumn('propaddress1', function ($appCard) {
                    return str_replace(',', ' ', $appCard->order->propaddress1);
                })
                ->editColumn('borrower', function ($appCard) {
                    return $appCard->order->borrower;
                })
                ->editColumn('trans_id', function ($appCard) {
                    return  $appCard->trans_id ? $appCard->trans_id : 'N\A';
                })
                ->editColumn('amount', function ($appCard) {
                    return $appCard->batchAmount;
                })
                ->make(true);
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getAppraisalChecks($from, $to, $type)
    {
        return $this->cimCheckPaymentRepo->getDailyBatchData($from, $to, $type);
    }

    /**
     * get apprasial cards for dataTable
     *
     * @return array $appraisalChecksDataTables
     */
    public function appraisalChecksDataTables($apprCheks)
    {
        
        return Datatables::of($apprCheks)
                ->editColumn('ref_type', function ($apprChek) {
                    return ucwords(strtolower(str_replace('_', ' ', $apprChek->ref_type)));
                })
                ->editColumn('created_date', function ($apprChek) {
                    return date('m/d/Y g:i A', $apprChek->created_date) ;
                })
                ->editColumn('order_id', function ($apprChek) {
                    return $apprChek->order_id;
                })
                ->editColumn('client', function ($apprChek) {
                    return $apprChek->order->groupData->descrip ;
                })
                ->editColumn('team', function ($apprChek) {
                    return $apprChek->order->adminTeamClient->adminTeam->descrip;
                })
                ->editColumn('propaddress1', function ($apprChek) {
                    return str_replace(',', ' ', $apprChek->order->propaddress1);
                })
                ->editColumn('borrower', function ($apprChek) {
                    return $apprChek->order->borrower;
                })
                ->editColumn('check_number', function ($apprChek) {
                    return  $apprChek->check_number;
                })
                ->editColumn('date_received', function ($apprChek) {
                    return  date('m/d/Y g:i A', $apprChek->date_received);
                })
                ->editColumn('amount', function ($apprChek) {
                    return $apprChek->batchAmount;
                })
                ->make(true);
    }


    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getMercury($from, $to, $type)
    {
        return $this->mercuryChargeRepo->getDailyBatchData($from, $to, $type);
    }

    /**
     * get mercury for dataTable
     *
     * @return array $appraisalCardsDataTables
     */
    public function mercuryDataTables($mercury)
    {
       
        return Datatables::of($mercury)
                ->editColumn('gateway', function ($mercury) {
                    return 'Mercury';
                })
                ->editColumn('ref_type', function ($mercury) {
                    return ucwords(strtolower($mercury->transaction_type));
                })
                ->editColumn('created_date', function ($mercury) {
                    return date('m/d/Y g:i A', $mercury->created_date);
                })
                ->editColumn('order_id', function ($mercury) {
                    return $mercury->order->id;
                })
                ->editColumn('client', function ($mercury) {
                    return $mercury->order->groupData->descrip;
                })
                ->editColumn('team', function ($mercury) {
                    return $mercury->order->adminTeamClient->adminTeam->descrip;
                })
                ->editColumn('propaddress1', function ($mercury) {
                    return str_replace(',', ' ', $mercury->order->propaddress1);
                })
                ->editColumn('borrower', function ($mercury) {
                    return $mercury->order->borrower;
                })
                ->editColumn('trans_id', function ($mercury) {
                    return  $mercury->transaction_id;
                })
                ->editColumn('amount', function ($mercury) {
                    return $mercury->batchAmount;
                })
                ->make(true);
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getAltCreditCards($from, $to, $type)
    {
        return $this->alCimPaymentRepo->getDailyBatchData($from, $to, $type);
    }

    /**
     * get mercury for dataTable
     *
     * @return array $altCreditCardsDataTables
     */
    public function altCreditCardsDataTables($altCreditCards)
    {
       
        return Datatables::of($altCreditCards)
                ->editColumn('gateway', function ($altCreditCard) {
                    return 'Authorize.net';
                })
                ->editColumn('ref_type', function ($altCreditCard) {
                    return ucwords(strtolower($altCreditCard->ref_type));
                })
                ->editColumn('created_date', function ($altCreditCard) {
                    return date('m/d/Y g:i A', $altCreditCard->created_date);
                })
                ->editColumn('order_id', function ($altCreditCard) {
                    return $altCreditCard->order->id;
                })
                ->editColumn('client', function ($altCreditCard) {
                    return $altCreditCard->order->groupData ? $altCreditCard->order->groupData->descrip : 'N\A';
                })
                ->editColumn('team', function ($altCreditCard) {
                    return $altCreditCard->order->adminTeamClient ? $altCreditCard->order->adminTeamClient->adminTeam->descrip : 'N\A';
                })
                ->editColumn('propaddress1', function ($altCreditCard) {
                    return str_replace(',', ' ', $altCreditCard->order->propaddress1);
                })
                ->editColumn('borrower', function ($altCreditCard) {
                    return $altCreditCard->order->borrower;
                })
                ->editColumn('trans_id', function ($altCreditCard) {
                    return  $altCreditCard->transaction_id;
                })
                ->editColumn('amount', function ($altCreditCard) {
                    return $altCreditCard->batchAmount;
                })
                ->make(true);
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getAltChecks($from, $to, $type)
    {
        return $this->alCimCheckPaymentRepo->getDailyBatchData($from, $to, $type);
    }

    /**
     * get mercury for dataTable
     *
     * @return array $altChecksDataTables
     */
    public function altChecksDataTables($altChecks)
    {

        return Datatables::of($altChecks)
                ->editColumn('ref_type', function ($altCheck) {
                    return ucwords(strtolower($altCheck->ref_type));
                })
                ->editColumn('created_date', function ($altCheck) {
                    return date('m/d/Y g:i A', $altCheck->created_date);
                })
                ->editColumn('order_id', function ($altCheck) {
                    return $altCheck->order->id;
                })
                ->editColumn('client', function ($altCheck) {
                    return $altCheck->order->groupData ? $altCheck->order->groupData->descrip : 'N\A';
                })
                ->editColumn('team', function ($altCheck) {
                    return $altCheck->order->adminTeamClient ? $altCheck->order->adminTeamClient->adminTeam->descrip : 'N\A';
                })
                ->editColumn('propaddress1', function ($altCheck) {
                    return str_replace(',', ' ', $altCheck->order->propaddress1);
                })
                ->editColumn('borrower', function ($altCheck) {
                    return $altCheck->order->borrower;
                })
                ->editColumn('check_number', function ($altCheck) {
                    return  $altCheck->check_number;
                })
                ->editColumn('check_number', function ($altCheck) {
                    return  date('m/d/Y g:i A', $altCheck->date_received);
                })
                ->editColumn('amount', function ($altCheck) {
                    return $altCheck->batchAmount;
                })
                ->make(true);
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getDocuvaultChecks($from, $to, $type)
    {
        return $this->docuvaultCheckPaymentRepo->getDailyBatchData($from, $to, $type);
    }

    /**
     * get mercury for dataTable
     *
     * @return array $docuvaultChecksDataTables
     */
    public function docuvaultChecksDataTables($docuvaultChecks)
    {

        return Datatables::of($docuvaultChecks)
                ->editColumn('ref_type', function ($docuvaultCheck) {
                    return ucwords(strtolower($docuvaultCheck->ref_type));
                })
                ->editColumn('created_date', function ($docuvaultCheck) {
                    return date('m/d/Y g:i A', $docuvaultCheck->created_date);
                })
                ->editColumn('order_id', function ($docuvaultCheck) {
                    return $docuvaultCheck->order->id;
                })
                ->editColumn('client', function ($docuvaultCheck) {
                    return $docuvaultCheck->order->groupData ? $docuvaultCheck->order->groupData->descrip : 'N\A';
                })
                ->editColumn('team', function ($docuvaultCheck) {
                    return $docuvaultCheck->order->adminTeamClient ? $docuvaultCheck->order->adminTeamClient->adminTeam->descrip : 'N\A';
                })
                ->editColumn('propaddress1', function ($docuvaultCheck) {
                    return str_replace(',', ' ', $docuvaultCheck->order->propaddress1);
                })
                ->editColumn('borrower', function ($docuvaultCheck) {
                    return $docuvaultCheck->order->borrower;
                })
                ->editColumn('check_number', function ($docuvaultCheck) {
                    return  $docuvaultCheck->check_number;
                })
                ->editColumn('check_number', function ($docuvaultCheck) {
                    return  date('m/d/Y g:i A', $docuvaultCheck->date_received);
                })
                ->editColumn('amount', function ($docuvaultCheck) {
                    return $docuvaultCheck->batchAmount;
                })
                ->make(true);
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return Builder
     */
    public function getAdjustments($from, $to, $type)
    {
        return $this->apprAccountingAdminRepo->getDailyBatchData($from, $to, $type);
    }

    /**
     * get mercury for dataTable
     *
     * @return array $adjustmentsDataTables
     */
    public function adjustmentsDataTables($adjustments)
    {

        return Datatables::of($adjustments)
                ->editColumn('created_date', function ($adjustment) {
                    return date('m/d/Y g:i A', $adjustment->created_date);
                })
                ->editColumn('order_id', function ($adjustment) {
                    return $adjustment->order->id;
                })
                ->editColumn('client', function ($adjustment) {
                    return $adjustment->order->groupData ? $adjustment->order->groupData->descrip : 'N\A';
                })
                ->editColumn('team', function ($adjustment) {
                    return $adjustment->order->adminTeamClient ? $adjustment->order->adminTeamClient->adminTeam->descrip : 'N\A';
                })
                ->editColumn('propaddress1', function ($adjustment) {
                    return str_replace(',', ' ', $adjustment->order->propaddress1);
                })
                ->editColumn('borrower', function ($adjustment) {
                    return $adjustment->order->borrower;
                })
                ->editColumn('reason', function ($adjustment) {
                    return  ucwords(strtolower(str_replace('_', ' ', $adjustment->reason))) ;
                })
                ->editColumn('type', function ($adjustment) {
                    return  ucwords(strtolower(str_replace('_', ' ', $adjustment->type)));
                })
                ->editColumn('check_number', function ($adjustment) {
                    return  $adjustment->check_number ;
                })
                ->editColumn('amount', function ($adjustment) {
                    return $adjustment->batchAmount;
                })
                ->make(true);
    }

    /**
     * @param $from
     * @param $to
     * @param $type
     * @return array
     */
    public function getExportData($from, $to, $type)
    {
        $this->exportApprCards = [];
        $this->getAppraisalCards($from, $to, $type)->chunk(1000, function (Collection $apprCreditCards) {
            foreach ($apprCreditCards as $card) {
                $this->exportApprCards[] = [
                    'created_date' => $card['created_date'],
                    'order_id' => $card['order_id'],
                    'prop_address' => $card['order']['propaddress1'],
                    'borrower' => $card['order']['borrower'],
                    'transaction_id' => $card['trans_id'],
                    'amount' => $card['batchAmount'],
                    'reference_type' => $card['ref_type'],
                    'client' => $card['order']['group_data']['descrip'],
                    'team' => $card['order']['group_data']['admin_team_client']['admin_team']['descrip'],
                    'gateway' => $card['gateway'],
                ];
            }
        });

        $this->exportMercury = [];
        $this->getMercury($from, $to, $type)->chunk(1000, function (Collection $mercury) {
            foreach ($mercury as $card) {
                $this->exportMercury[] = [
                    'created_date' => $card->created_date,
                    'order_id' => $card->lni_id,
                    'prop_address' => $card->order->propaddress1,
                    'borrower' => $card->order->borrower,
                    'transaction_id' => $card->transaction_id,
                    'amount' => $card->batchAmount,
                    'reference_type' => $card->transaction_type,
                    'client' => $card->order->groupData->descrip,
                    'team' => $card->order->groupData->adminTeamClient->adminTeam->descrip,
                    'gateway' => 'Mercury',
                ];
            }
        });

        $this->exportApprChecks = [];
        $this->getAppraisalChecks($from, $to, $type)->chunk(1000, function (Collection $appraisalChecks) {
            foreach ($appraisalChecks as $check) {
                $this->exportApprChecks[] = [
                    'created_date' => $check->created_date,
                    'order_id' => $check->order_id,
                    'property_address' => $check->order->propaddress1,
                    'borrower' => $check->order->borrower,
                    'check_number' => $check->check_number,
                    'date_received' => $check->date_received,
                    'amount' => $check->batchAmount,
                    'reference_type' => $check->ref_type,
                    'client' => optional($check->order->groupData)->descrip,
                    'team' => optional(optional($check->order->adminTeamClient)->adminTeam)->descrip,
                ];
            }
        });

        $this->exportAltCreditCards = [];
        $this->getAltCreditCards($from, $to, $type)->chunk(1000, function (Collection $altCreditCards) {
            foreach ($altCreditCards as $card) {
                $this->exportAltCreditCards[] = [
                    'created_date' => $card->created_date,
                    'order_id' => $card->order_id,
                    'prop_address' => $card->order->propaddress1,
                    'borrower' => $card->order->borrower,
                    'transaction_id' => $card->trans_id,
                    'amount' => $card->batchAmount,
                    'reference_type' => $card->ref_type,
                    'client' => optional($card->order->groupData)->descrip,
                    'team' => optional(optional($card->order->adminTeamClient)->adminTeam)->descrip,
                    'gateway' => 'Authorize.net',
                ];
            }
        });

        $this->exportAltChecks = [];
        $this->getAltChecks($from, $to, $type)->chunk(1000, function (Collection $altChecks) {
            foreach ($altChecks as $check) {
                $this->exportAltChecks[] = [
                    'created_date' => $check->created_date,
                    'order_id' => $check->order_id,
                    'property_address' => $check->order->propaddress1,
                    'borrower' => $check->order->borrower,
                    'check_number' => $check->check_number,
                    'date_received' => $check->date_received,
                    'amount' => $check->batchAmount,
                    'reference_type' => $check->ref_type,
                    'client' => optional($check->order->groupData)->descrip,
                    'team' => optional(optional($check->order->adminTeamClient)->adminTeam)->descrip,
                ];
            }
        });

        $this->exportDocuvaultChecks = [];
        $this->getDocuvaultChecks($from, $to, $type)->chunk(1000, function (Collection $docuvaultChecks) {
            foreach ($docuvaultChecks as $check) {
                $this->exportDocuvaultChecks[] = [
                    'created_date' => $check->created_date,
                    'order_id' => $check->order_id,
                    'property_address' => $check->order->propaddress1,
                    'borrower' => $check->order->borrower,
                    'check_number' => $check->check_number,
                    'date_received' => $check->date_received,
                    'amount' => $check->batchAmount,
                    'reference_type' => $check->ref_type,
                    'client' => optional($check->order->groupData)->descrip,
                    'team' => optional(optional($check->order->adminTeamClient)->adminTeam)->descrip,
                ];
            }
        });

        $this->exportAdjustments = [];
        $this->getAdjustments($from, $to, $type)->chunk(1000, function (Collection $adjustments) {
            foreach ($adjustments as $adjustment) {
                $this->exportAdjustments[] = [
                    'created_date' => $adjustment->created_date,
                    'order_id' => $adjustment->order_id,
                    'property_address' => $adjustment->order->propaddress1,
                    'borrower' => $adjustment->order->borrower,
                    'check_number' => $adjustment->check_number,
                    'amount' => $adjustment->batchAmount,
                    'client' => optional($adjustment->order->groupData)->descrip,
                    'team' => optional(optional($adjustment->order->adminTeamClient)->adminTeam)->descrip,
                    'reason' => $adjustment->reason,
                    'type' => $adjustment->type,
                    'note' => $adjustment->note,
                ];
            }
        });

        return [
            $this->exportApprCards,
            $this->exportMercury,
            $this->exportApprChecks,
            $this->exportAltCreditCards,
            $this->exportAltChecks,
            $this->exportDocuvaultChecks,
            $this->exportAdjustments
        ];
    }

    /**
     * @param Collection $collection
     * @param $page
     * @param $limit
     * @return LengthAwarePaginator
     */
    public function getPaginator(Collection $collection, $page, $limit)
    {
        return new LengthAwarePaginator(
            $collection->forPage($page, $limit),
            $collection->count(),
            $limit,
            $page
        );
    }
}
