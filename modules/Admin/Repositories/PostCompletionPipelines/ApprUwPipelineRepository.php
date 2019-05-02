<?php

namespace Modules\Admin\Repositories\PostCompletionPipelines;

use App\Models\Tiger\Amc;
use App\Models\Users\User;
use App\Models\Tools\Setting;
use App\Models\Clients\Client;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;
use App\Models\Documents\DocumentType;
use App\Models\Customizations\Type;
use App\Services\OrderFunctionsService;
use App\Models\Customizations\Status;
use App\Models\Customizations\LoanReason;
use App\Models\Management\AdminTeamsManager\AdminTeam;
use App\Models\ManagerReports\QCReport\QCReport;
use App\Models\ManagerReports\QCReport\ApprQcLog;
use App\Models\Management\AdminGroup\AdminGroupPermission;
use App\Models\Management\AdminGroup\AdminPermissionCategory;
use App\Models\Appraisal\ClientPermissions;
use App\Models\Appraisal\UserPermissions;
use App\Models\Appraisal\Order;
use App\Models\Appraisal\OrderFile;
use App\Models\Appraisal\AppointmentSchedule;
use App\Models\Appraisal\ApprOrderNotes;
use App\Models\Appraisal\EAD\EadUnitClientRel;
use App\Models\Appraisal\EAD\EadUnitLenderRel;
use App\Models\Appraisal\EAD\EadUnitAppraisalTypeRel;
use App\Models\Appraisal\EAD\EadUnitLoanTypeRel;
use App\Models\Appraisal\EAD\Unit;
use App\Models\Appraisal\UW\ApprOrderEad;
use App\Models\Appraisal\UW\ApprUwCategory;
use App\Models\Appraisal\UW\ApprUwAnswers;
use App\Models\Appraisal\UW\ApprUwConditions;
use App\Models\Appraisal\UW\ApprUwStats;
use App\Models\Appraisal\UW\UW;
use App\Models\Appraisal\UW\ApprUwContacts;
use App\Models\Appraisal\UW\ApprUwQcLog;
use App\Models\Appraisal\UW\ApprUwGeneralAnswers;
use App\Models\Appraisal\UW\Checklist;
use App\Models\Appraisal\UCDP\UCDP;
use App\Models\Appraisal\UCDP\UcdpUnitClientRel;
use App\Models\Appraisal\UCDP\UcdpUnitLenderRel;
use App\Models\Appraisal\UCDP\UcdpUnitAppraisalTypeRel;
use App\Models\Appraisal\UCDP\UcdpUnitLoanTypeRel;
use App\Models\Appraisal\UCDP\UCDPUnit;
use App\Models\Appraisal\UCDP\UCDPUnitFnmSSN;
use App\Models\Appraisal\UCDP\UCDPUnitFreSSN;
use App\Models\Appraisal\QC\ApprQc;
use App\Models\Appraisal\QC\ApprQcRealViewOrder;
use App\Models\Appraisal\QC\ApprQcOrderCorrections;
use App\Models\Appraisal\QC\ApprQcRealViewOrderActivity;
use App\Models\Appraisal\QC\ApprQcRealViewOrderChecklist;
use App\Models\Appraisal\QC\ApprQcRealviewOrderScore;
use App\Models\Appraisal\QC\DataQuestion;
use App\Models\Appraisal\QC\DataAnswer;
use App\Models\Appraisal\QC\QcAnswer;
use App\Models\Appraisal\QC\QcAnswerHistory;
use App\Models\Appraisal\QC\DataQuestionLoanReason;
use App\Models\Appraisal\QC\DataQuestionLoan;
use App\Models\Appraisal\QC\DataQuestionAppr;
use App\Models\Appraisal\QC\DataQuestionLender;
use App\Models\Appraisal\QC\DataQuestionClient;
use App\Models\Management\WholesaleLenders\UserGroupLender;

class ApprUwPipelineRepository
{
    protected $order;
    protected $user;
    protected $appUw;
    protected $appLog;
    protected $appStats;
    protected $checklist;
    protected $ucdpUnitClientRel;
    protected $ucdpUnitLenderRel;
    protected $ucdpUnitAppraisalTypeRel;
    protected $ucdpUnitLoanTypeRel;
    protected $ucdpUnitFnmSSN;
    protected $ucdpUnitFreSSN;
    protected $eadUnitClientRel;
    protected $eadUnitLenderRel;
    protected $eadUnitAppraisalTypeRel;
    protected $eadUnitLoanTypeRel;


    /**
     * LoanTypesRepository constructor.
     */
    public function __construct()
    {
        $this->order = new Order();
        $this->user = new User();
        $this->appUw = new ApprUwConditions();
        $this->appLog = new ApprQcLog();
        $this->appStats = new ApprUwStats();
        $this->checklist = new Checklist();
        $this->ucdpUnitClientRel = new UcdpUnitClientRel();
        $this->ucdpUnitLenderRel = new UcdpUnitLenderRel();
        $this->ucdpUnitAppraisalTypeRel = new UcdpUnitAppraisalTypeRel();
        $this->ucdpUnitLoanTypeRel = new UcdpUnitLoanTypeRel();
        $this->eadUnitClientRel = new EadUnitClientRel();
        $this->eadUnitLenderRel = new EadUnitLenderRel();
        $this->eadUnitAppraisalTypeRel = new EadUnitAppraisalTypeRel();
        $this->eadUnitLoanTypeRel = new EadUnitLoanTypeRel();
        $this->ucdpUnitFnmSSN = new UCDPUnitFnmSSN();
        $this->ucdpUnitFreSSN = new UCDPUnitFreSSN();
    }

    /**
     * We return the latest one
     */
    public function getRealViewFullPDFReport($id)
    {
        return OrderFile::select('id', 'filename')
            ->where('document_type', getDocumentTypeIdByCode('REALVIEWPDFREPORT'))
            ->where('order_id', $id)
            ->orderBy('created_at', 'DESC')->first();
    }

    /**
     * We return the latest one
     */
    public function getRealViewPDFReport($id)
    {
        return OrderFile::select('id', 'filename')
            ->where('document_type', getDocumentTypeIdByCode('REALVIEWPDFREPORTSUMMARY'))
            ->where('order_id', $id)
            ->orderBy('created_at', 'DESC')->first();
    }

    /**
     * We return the latest one
     */
    public function getRealViewXMLReport($id)
    {
        return OrderFile::select('id', 'filename')
            ->where('document_type', getDocumentTypeIdByCode('REALVIEWXML'))
            ->where('order_id', $id)
            ->orderBy('created_at', 'DESC')->first();
    }

    /**
     * We return the latest one
     */
    public function getRealViewHTMLReport($id)
    {
        return OrderFile::select('id', 'filename')
            ->where('document_type', getDocumentTypeIdByCode('REALVIEWHTMLREPORT'))
            ->where('order_id', $id)
            ->orderBy('created_at', 'DESC')->first();
    }

    /**
     * Return the real activity
     */
    public function getRealViewScores($id)
    {
        return ApprQcRealviewOrderScore::where('order_id', $id)->orderBy('created_date', 'DESC')->first();
    }

    /**
     * Try to figure out the appraisal business unit for ucdp
     * based on lender_id if we have it or groupid otherwise
     */
    public function getAppraisalOrderBusinessUnit($order)
    {
        $row = null;

        if ($order->lender_id && $order->lender_id > 0) {
            $row = UCDPUnit::select('ucdp_unit.unit_id')
                ->leftJoin('ucdp_unit_lender_rel as l', 'l.rel_id', '=', 'ucdp_unit.id')
                ->where('l.lender_id', $order->lender_id)
                ->first();
        }

        if (!$row && $order->groupid && $order->groupid > 0) {
            $row = UCDPUnit::select('ucdp_unit.unit_id')
                ->leftJoin('ucdp_unit_client_rel as l', 'l.rel_id', '=', 'ucdp_unit.id')
                ->where('l.client_id', $order->groupid)
                ->first();
        }

        if ($row) {
            return $row->unit_id;
        }

        return null;
    }

    /**
     * Return previous submission order record
     */
    public function getUCDPOrderRecord($id)
    {
        return UCDP::where('order_id', $id)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUCDPFNMReport($id)
    {
        $documentType = DocumentType::where('code', 'UCDPFNMSSR')->first();
        return OrderFile::select('id', 'filename')->where('order_id', $id)->where('document_type', $documentType->id)->orderBy('created_at', 'desc')->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUCDPFREReport($id)
    {
        $documentType = DocumentType::where('code', 'UCDPFRESSR')->first();
        return OrderFile::select('id', 'filename')->where('order_id', $id)->where('document_type', $documentType->id)->orderBy('created_at', 'desc')->first();
    }

    /**
     * @param $order
     * @return null
     */
    public function getAppraisalOrderEADFHALenderId($order)
    {
        $row = null;

        if ($order->lender_id && $order->lender_id > 0) {
            $row = Unit::select('ead_unit.fha_lenderid')
                ->leftJoin('ead_unit_lender_rel', 'ead_unit_lender_rel.rel_id', '=', 'ead_unit.id')
                ->where('ead_unit_lender_rel.lender_id', $order->lender_id)->first();
        }

        if (!$row && $order->groupid && $order->groupid > 0) {
            $row = Unit::select('ead_unit.fha_lenderid')
                ->leftJoin('ead_unit_client_rel', 'ead_unit_client_rel.rel_id', '=', 'ead_unit.id')
                ->where('ead_unit_client_rel.client_id', $order->groupid)->first();
        }

        if ($row) {
            return $row->fha_lenderid;
        }

        return null;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getEADOrderRecord($id)
    {
        return ApprOrderEad::all()->where('order_id', '=', $id)->first();
    }

    /**
     * @param $order
     * @return mixed
     */
    public function getOrderFinalAWSReportXML($order)
    {
        if ($order->revision) {
            $row = OrderFile::where('order_id', $order->id)
                ->where('is_xml', 1)
                ->where('revision', $order->revision)
                ->first();
        } else {
            $row = OrderFile::where('order_id', $order->id)
                ->where('is_xml', 1)
                ->orderBy('id')
                ->first();
        }
        return $row;
    }

    /**
     * Get Teams
     * @return collection
     */
    public function getTeams()
    {
        return AdminTeam::orderBy('team_title', 'ASC')->get();
    }

    /**
     * Get Appraisal UW Records
     * @return collection
     */
    public function getAppraisalUWRecords($status, $sort = false, $count = false)
    {
        $query = $this->order->query()
            ->with(['apprType'])
            ->select(
            'appr_order.id',
            'appr_order.status',
            'appr_order.propaddress1',
            'appr_order.propaddress2',
            'appr_order.propcity',
            'appr_order.propstate',
            'appr_order.propzip',
            'appr_order.ordereddate',
            'appr_order.submitted',
            'appr_order.loanpurpose',
            'appr_order.is_cod',
            'appr_order.cod_confirm',
            'appr_order.billmelater',
            'appr_order.is_rush',
            'appr_order.due_date',
            'appr_order.appr_type',
            'appr_order.invoicedue',
            'appr_order.paid_amount',
            'appr_order.revision',
            'appr_order.qc_type',
            'appr_order.uw_assigned_to',
            'g.descrip as client_fullname',
            \DB::raw("CONCAT(t.form,' ',t.short_descrip,' - ',l.descrip) as order_appr_type"),
            'at.id as appr_team',
            'at.descrip as appr_team_title',
            'at.qc_uw_pipeline_color',
            'log.dts',
            'appr_order.groupid',
            'company.company',
            'assigned.firstname as assigned_firstname',
            'assigned.lastname as assigned_lastname',
            'record.id as record_id',
            'record.created_date as record_created_date',
            'record.locked_by as record_locked_by',
            'locked.firstname as locked_firstname',
            'locked.lastname as locked_lastname',
            'ead.is_error as ead_is_error',
            'ead.is_completed as ead_is_completed',
            'ead.id as ead_id',
            'rv.overall as rv_overall',
            'uc.risk_score as uc_risk_score',
            'uc_risk.is_error as uc_risk_is_error',
            'uc_risk.is_completed as uc_risk_is_completed',
            'uc_risk.id as uc_risk_id',
            'record.is_saved as record_is_saved',
            'record.is_hold as record_is_hold',
            'record.is_cu_risk_hold as record_is_cu_risk_hold'
        )
            ->leftJoin('user_groups as g', 'g.id', '=', 'appr_order.groupid')
            ->leftJoin('appr_type as t', 't.id', '=', 'appr_order.appr_type')
            ->leftJoin('loantype as l', 'l.id', '=', 'appr_order.loantype')
            ->leftJoin('admin_team_client as atc', 'atc.user_group_id', '=', 'appr_order.groupid')
            ->leftJoin('admin_teams as at', 'atc.team_id', '=', 'at.id')
            ->leftJoin('user_groups as company', 'company.id', '=', 'appr_order.groupid')
            ->leftJoin('user_data as assigned', 'assigned.user_id', '=', 'appr_order.uw_assigned_to')
            ->leftJoin(
                'order_log as log',
                function ($join) {
                    $join->whereRaw('(`log`.`orderid` = `appr_order`.`id` and `info` REGEXP CONCAT("(Appraiser|Admin) Uploaded Appraisal rev ", `appr_order`.`revision`))');
                }
            )
            ->leftJoin(
                'appr_uw as record',
                function ($join) {
                    $join->whereRaw('(`record`.`order_id` = `appr_order`.`id` and `record`.`is_complete` = 0)');
                }
            )
            ->leftJoin(
                'appraisal_order_remote_pending_submission as ead',
                function ($join) {
                    $join->whereRaw('(`ead`.`order_id` = `appr_order`.`id` and `ead`.`type` = "ead")');
                }
            )
            ->leftJoin(
                'appraisal_order_remote_pending_submission as uc_risk',
                function ($join) {
                    $join->whereRaw('(`uc_risk`.`order_id` = `appr_order`.`id` and `uc_risk`.`type` = "ucdp")');
                }
            )
            ->leftJoin('appr_order_ucdp as uc', 'uc.order_id', '=', 'appr_order.id')
            ->leftJoin('appr_qc_realview_order_score as rv', 'rv.order_id', '=', 'appr_order.id')
            ->leftJoin('user_data as locked', 'locked.user_id', '=', 'record.locked_by')
            ->where('appr_order.status', $status);


        if ($count) {
            return count($query->groupBy('appr_order.id')->orderBy('log.dts', 'ASC')->get());
        }
        if ($sort) {
            return $query->groupBy('appr_order.id')->orderBy('log.dts', 'ASC')->get();
        }

        $rows = $query->groupBy('appr_order.id')->get();
        $rows->each->setAppends([]);
        return $rows;
    }

    /**
     * Get UW Report Records
     * @return collection
     */
    public function getUWReportRecords($from, $to, $type = 'date_uw_received', $clients = [])
    {
        $query = $this->appUw->select(
            'a.id',
            'a.loanrefnum',
            \DB::raw("CONCAT(t.form,'-',t.descrip) as appr_type"),
            'loan.descrip as loan_type',
            'g.descrip as client',
            \DB::raw("CONCAT(appr.firstname,' ',appr.lastname) as appr_name"),
            'a.propaddress1',
            'a.propcity',
            'a.propstate',
            'a.propzip',
            'a.date_uw_received',
            'a.date_uw_completed',
            'a.date_delivered',
            'uc.title',
            'appr_uw_conditions.cond'
        )
            ->leftJoin('appr_uw as uw', 'uw.id', '=', 'appr_uw_conditions.uw_id')
            ->leftJoin('appr_order as a', 'a.id', '=', 'uw.order_id')
            ->leftJoin('loantype as loan', 'loan.id', '=', 'a.loantype')
            ->leftJoin('appr_type as t', 't.id', '=', 'a.appr_type')
            ->leftJoin('user as u', 'u.id', '=', 'a.orderedby')
            ->leftJoin('user_groups as g', 'g.id', '=', 'a.groupid')
            ->leftJoin('user as appr_user', 'appr_user.id', '=', 'a.acceptedby')
            ->leftJoin('user_data as appr', 'appr_user.id', '=', 'appr.user_id')
            ->leftJoin('uw_category as uc', 'appr_uw_conditions.category', '=', 'uc.key')
            ->whereBetween(sprintf('a.%s', $type), [date('Y-m-d 00:00:00', $from), date('Y-m-d 23:59:59', $to)]);

        if (count($clients)) {
            $query = $query->whereIn('g.id', $clients);
        }

        return $query->get();
    }

    /**
     * Get QC Approved By Order Id
     * @return collection
     */
    public function getQCApprovedByOrderId($id)
    {
        $query = $this->appLog->select('u.id', \DB::raw("CONCAT(o.firstname,' ', o.lastname) as name"))
            ->leftJoin('user as u', 'u.id', '=', 'appr_qc_log.created_userid')
            ->leftJoin('user_data as o', 'u.id', '=', 'o.user_id')
            ->where('appr_qc_log.is_approved', 1)
            ->where('appr_qc_log.order_id', intval($id));

        return $query->first();
    }

    /**
     * Get Appr UW Stats By Date
     * @return collection
     */
    public function getApprUWStatsByDate($from, $to)
    {
        return $this->appStats->select('appr_uw_stats.*', \DB::raw("CONCAT(o.firstname,' ', o.lastname) as user_name"))
            ->leftJoin('user_data as o', 'appr_uw_stats.created_by', '=', 'o.user_id')
            ->where('created_date', '>=', $from)
            ->where('created_date', '<=', $to)->get();
    }

    /**
     * Get Daily Break Down From Rows
     * @return array
     */
    public function getDailyBreakDownFromRows($rows)
    {
        $list = [];
        $users = [];
        if ($rows && count($rows)) {
            foreach ($rows as $row) {
                $users[$row->created_by][] = $row;
            }
        }
        $totals = [
            'first' => 0,
            'second' => 0,
            'back' => 0,
            'first_time' => 0,
            'second_time' => 0,
            'back_time' => 0,
            'total_time' => 0,
            'total' => 0
        ];

        foreach ($users as $userId => $data) {
            $list[$userId]['user_name'] = isset($data[0]) ? $data[0]->user_name : '';
            foreach ($data as $r) {
                isset($list[$userId]['first']) ? $list[$userId]['first'] += $r->first_approved : $list[$userId]['first'] = $r->first_approved;
                isset($list[$userId]['second']) ? $list[$userId]['second'] += $r->second_approved : $list[$userId]['second'] = $r->second_approved;
                isset($list[$userId]['back']) ? $list[$userId]['back'] += $r->sent_back : $list[$userId]['back'] = $r->sent_back;

                isset($list[$userId]['first_time']) ?
                    $list[$userId]['first_time'] += $r->first_approved ? $this->getApprUWTimeSeconds($r->time_taken) : 0 :
                    $list[$userId]['first_time'] = $r->first_approved ? $this->getApprUWTimeSeconds($r->time_taken) : 0;
                isset($list[$userId]['back_time']) ?
                    $list[$userId]['back_time'] += $r->sent_back ? $this->getApprUWTimeSeconds($r->time_taken) : 0 :
                    $list[$userId]['back_time'] = $r->sent_back ? $this->getApprUWTimeSeconds($r->time_taken) : 0;
                isset($list[$userId]['second_time']) ?
                    $list[$userId]['second_time'] += $r->second_approved ? $this->getApprUWTimeSeconds($r->time_taken) : 0 :
                    $list[$userId]['second_time'] = $r->second_approved ? $this->getApprUWTimeSeconds($r->time_taken) : 0;
                isset($list[$userId]['total_time']) ?
                    $list[$userId]['total_time'] += $this->getApprUWTimeSeconds($r->time_taken) :
                    $list[$userId]['total_time'] = $this->getApprUWTimeSeconds($r->time_taken);

                isset($list[$userId]['total']) ? $list[$userId]['total'] += 1 : $list[$userId]['total'] = 1;

                $totals['first'] += $r->first_approved;
                $totals['second'] += $r->second_approved;
                $totals['back'] += $r->sent_back;
                $totals['first_time'] += $r->first_approved ? $this->getApprUWTimeSeconds($r->time_taken) : 0;
                $totals['second_time'] += $r->sent_back ? $this->getApprUWTimeSeconds($r->time_taken) : 0;
                $totals['back_time'] += $r->second_approved ? $this->getApprUWTimeSeconds($r->time_taken) : 0;
                $totals['total_time'] += $this->getApprUWTimeSeconds($r->time_taken);
                $totals['total'] += 1;
            }
        }

        if (count($list)) {
            foreach ($list as $userId => $elem) {
                $list[$userId]['avg_first'] = $elem['first'] ? floor($elem['first_time'] / $elem['first']) : 0;
                $list[$userId]['avg_back'] = $elem['back'] ? floor($elem['back_time'] / $elem['back']) : 0;
                $list[$userId]['avg_second'] = $elem['second'] ? floor($elem['second_time'] / $elem['second']) : 0;
                $list[$userId]['avg_total'] = $elem['total'] ? floor($elem['total_time'] / $elem['total']) : 0;
            }
        }

        $totals['total_avg'] = $totals['total'] ? floor($totals['total_time'] / $totals['total']) : 0;
        $totals['avg_first'] = $totals['first'] ? floor($totals['first_time'] / $totals['first']) : 0;
        $totals['avg_back'] = $totals['back'] ? floor($totals['back_time'] / $totals['back']) : 0;
        $totals['avg_second'] = $totals['second'] ? floor($totals['second_time'] / $totals['second']) : 0;

        return array('list' => $list, 'totals' => $totals);
    }

    /**
     * Get Appr UW Orders Having Conditions
     * @return collection
     */
    public function getApprUWOrdersHavingConditions($from, $to)
    {
        return $this->appUw->select(\DB::raw("COUNT(DISTINCT u.order_id) as total"))
            ->leftJoin('appr_uw as u', 'appr_uw_conditions.uw_id', '=', 'u.id')
            ->where('appr_uw_conditions.created_date', '>=', $from)
            ->where('appr_uw_conditions.created_date', '<=', $to)
            ->where('appr_uw_conditions.is_approved', 1)->first();
    }

    /**
     * Get Appr UW Orders Conditions
     * @return collection
     */
    public function getApprUWOrdersConditions($from, $to)
    {
        return $this->appUw->select(\DB::raw('COUNT(id) as total'))
            ->where('created_date', '>=', $from)
            ->where('created_date', '<=', $to)
            ->where('is_approved', 1)->first();
    }

    /**
     * Get Appr UW Condition Users
     * @return collection
     */
    public function getApprUWConditionUsers($from, $to)
    {
        $rows = $this->appUw->select('appr_uw_conditions.created_by', \DB::raw("CONCAT(o.firstname,' ', o.lastname) as user_name"))
            ->leftJoin('user_data as o', 'appr_uw_conditions.created_by', '=', 'o.user_id')
            ->where('created_date', '>=', $from)->where('created_date', '<=', $to)->groupBy('created_by')->get();
        $list = [];
        if ($rows && count($rows)) {
            foreach ($rows as $r) {
                $list[$r->created_by]['user_name'] = $r->user_name;
                $list[$r->created_by]['userConditionsDistinct'] = $this->getApprUWOrdersHavingConditionsByUserId($from, $to, $r->created_by);
                $list[$r->created_by]['userConditions'] = $this->getApprUWOrdersConditionsByUserId($from, $to, $r->created_by);
            }
        }
        return $list;
    }

    /**
     * Get Appr UW Orders Having Conditions By User Id
     * @return collection
     */
    public function getApprUWOrdersHavingConditionsByUserId($from, $to, $userId)
    {
        $row = $this->appUw->select(\DB::raw('COUNT(DISTINCT u.order_id) as total'))
            ->leftJoin('appr_uw as u', 'appr_uw_conditions.uw_id', '=', 'u.id')
            ->where('appr_uw_conditions.created_date', '>=', $from)
            ->where('appr_uw_conditions.created_date', '<=', $to)
            ->where('appr_uw_conditions.created_by', $userId)
            ->where('appr_uw_conditions.is_approved', 1)->first();
        return $row->total;
    }

    /**
     * Get Appr UW Orders Conditions By User Id
     * @return collection
     */
    public function getApprUWOrdersConditionsByUserId($from, $to, $userId)
    {
        $row = $this->appUw->select(\DB::raw('COUNT(id) as total'))
            ->where('created_date', '>=', $from)
            ->where('created_date', '<=', $to)
            ->where('created_by', '<=', $userId)
            ->where('is_approved', 1)->first();
        return $row->total;
    }

    /**
     * Get UW Stats By User Id
     * @return collection
     */
    public function getUWStatsByUserId($from, $to, $userId)
    {
        $rows = $this->appStats->select('appr_uw_stats.*', 'o.propaddress1', 'o.propaddress2', 'o.propcity', 'o.propstate')
            ->leftJoin('appr_order as o', 'o.id', '=', 'appr_uw_stats.order_id')
            ->where('created_date', '>=', $from)
            ->where('created_date', '<=', $to)
            ->where('created_by', $userId)->get();
        foreach ($rows as $row) {
            $row->address = $this->getOrderAddress($row);
            $row->result = $this->getApprUWStatsResultTitle($row);
        }
        return $rows;
    }

    /**
     * Get UW Conditions Stats By User Id
     * @return collection
     */
    public function getUWConditionsStatsByUserId($from, $to, $userId)
    {
        $rows = $this->appUw->select('appr_uw_conditions.*', 'u.order_id', \DB::raw("COUNT(appr_uw_conditions.id) as total"), 'o.propaddress1', 'o.propaddress2', 'o.propcity', 'o.propstate')
            ->leftJoin('appr_uw as u', 'appr_uw_conditions.uw_id', '=', 'u.id')
            ->leftJoin('appr_order as o', 'o.id', '=', 'u.order_id')
            ->where('appr_uw_conditions.created_date', '>=', $from)
            ->where('appr_uw_conditions.created_date', '<=', $to)
            ->where('appr_uw_conditions.created_by', '<=', $userId)
            ->where('appr_uw_conditions.is_approved', 1)->groupBy('u.order_id')->get();
        foreach ($rows as $row) {
            $row->address = $this->getOrderAddress($row);
        }
        return $rows;
    }

    /**
     * Data for Awaiting Approval Tab
     * @return view
     */
    public function dataAwaitingApproval()
    {
        $that = $this;
        $awaitingApprovals = $that->getAppraisalUWRecords(Order::STATUS_UW_APPROVAL, true);

        return Datatables::of($awaitingApprovals)
            ->editColumn('company', function ($r) {
                return !is_null($r->company) ? $r->company : 'N/A';
            })
            ->addColumn('address', function ($r) {
                return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._address', ['row' => $r]);
            })
            ->editColumn('due_date', function ($r) {
                return $r->due_date > 0 ? date("m/d/y g:i A", $r->due_date) : '';
            })
            ->editColumn('assigned_to', function ($r) {
                return $r->uw_assigned_to ? $r->assigned_firstname . ' ' . $r->assigned_lastname : '';
            })
            ->editColumn('locked_by', function ($r) {
                return $r->record_locked_by ? $r->locked_firstname . ' ' . $r->locked_lastname : '';
            })
            ->editColumn('created_date', function ($r) {
                return $r->record_created_date ? date("M j g:i A", $r->record_created_date) : 'N/A';
            })
            ->editColumn('last_uploaded', function ($r) {
                return $r->dts ? date("M j g:i A", strtotime($r->dts)) : '';
            })
            ->editColumn('rv_overall', function ($r) {
                return !is_null($r->rv_overall) ? $r->rv_overall : '--';
            })
            ->editColumn('uc_risk_score', function ($r) {
                if (!is_null($r->uc_risk_score)) {
                    return $r->uc_risk_score;
                } else {
                    if (!is_null($r->uc_risk_id)) {
                        if ($r->uc_risk_is_error) {
                            return "<i class='fa fa-times fa-lg text-danger'></i>";
                        } elseif ($r->uc_risk_is_completed && !$r->uc_risk_is_error) {
                            return "<i class='fa fa-check fa-lg text-success'></i>";
                        } else {
                            return "<i class='fa fa-spinner fa-lg fa-spin fa-fw'></i>";
                        }
                    } else {
                        return '--';
                    }
                };
            })
            ->editColumn('ead', function ($r) {
                if (!is_null($r->ead_id)) {
                    if ($r->ead_is_error) {
                        return "<i class='fa fa-times fa-lg text-danger'></i>";
                    } elseif ($r->ead_is_completed && !$r->ead_is_error) {
                        return "<i class='fa fa-check fa-lg text-success'></i>";
                    } else {
                        return "<i class='fa fa-spinner fa-lg fa-spin fa-fw'></i>";
                    }
                } else {
                    return '--';
                }
            })
            ->setRowClass(function ($r) use ($that) {
                if ($r->record_id) {
                    $class = $that->getPipelineClasses($r);
                    if ($r->record_locked_by && $r->record_locked_by != getUserId()) {
                        return $class . ' uw-locked';
                    } else {
                        return $class . ' view-approval';
                    }
                }
            })
            ->setRowAttr([
                'id' => function ($r) {
                    if ($r->record_id) {
                        return 'approval-' . $r->record_id;
                    }
                },
            ])
            ->make(true);
    }

    /**
     * Data for Pending Corrections Tab
     * @return view
     */
    public function dataPendingCorrections()
    {
        $pendingCorrections = $this->getAppraisalUWRecords(Order::STATUS_UW_CONDITION);

        return Datatables::of($pendingCorrections)
            ->editColumn('company', function ($r) {
                return !is_null($r->company) ? $r->company : 'N/A';
            })
            ->addColumn('address', function ($r) {
                return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._address', ['row' => $r]);
            })
            ->editColumn('due_date', function ($r) {
                return $r->due_date > 0 ? date("m/d/y g:i A", $r->due_date) : '';
            })
            ->editColumn('created_date', function ($r) {
                return $r->record_created_date ? date("M j g:i A", $r->record_created_date) : 'N/A';
            })
            ->editColumn('last_uploaded', function ($r) {
                return $r->dts ? date("M j g:i A", strtotime($r->dts)) : '';
            })
            ->editColumn('rv_overall', function ($r) {
                return !is_null($r->rv_overall) ? $r->rv_overall : '--';
            })
            ->editColumn('uc_risk_score', function ($r) {
                if (!is_null($r->uc_risk_score)) {
                    return $r->uc_risk_score;
                } else {
                    if (!is_null($r->uc_risk_id)) {
                        if ($r->uc_risk_is_error) {
                            return "<i class='fa fa-times fa-lg text-danger'></i>";
                        } elseif ($r->uc_risk_is_completed && !$r->uc_risk_is_error) {
                            return "<i class='fa fa-check fa-lg text-success'></i>";
                        } else {
                            return "<i class='fa fa-spinner fa-lg fa-spin fa-fw'></i>";
                        }
                    } else {
                        return '--';
                    }
                };
            })
            ->editColumn('ead', function ($r) {
                if (!is_null($r->ead_id)) {
                    if ($r->ead_is_error) {
                        return "<i class='fa fa-times fa-lg text-danger'></i>";
                    } elseif ($r->ead_is_completed && !$r->ead_is_error) {
                        return "<i class='fa fa-check fa-lg text-success'></i>";
                    } else {
                        return "<i class='fa fa-spinner fa-lg fa-spin fa-fw'></i>";
                    }
                } else {
                    return '--';
                }
            })
            ->make(true);
    }

    /**
     * Daily Combo Chart Data
     * @return array
     */
    public function dailyComboChartData()
    {
        $month = date("Y-m-t");
        $fromUnix = strtotime(sprintf('%s-%s-01', date('Y', strtotime($month)), date('m', strtotime($month))) . ' 00:00:00');
        $toUnix = strtotime(sprintf('%s-%s-31', date('Y', strtotime($month)), date('m', strtotime($month))) . ' 23:59:59');
        $items = [['from' => $fromUnix, 'to' => $toUnix, 'dateFrom' => date('m/d/Y', $fromUnix), 'dateTo' => date('m/d/Y', $fromUnix)]];

        $months = 6;
        for ($i = 1; $i <= $months; $i++) {
            $month = date("Y-m-t", strtotime("-" . $i . " month"));
            $fromUnix = strtotime(sprintf('%s-%s-01', date('Y', strtotime($month)), date('m', strtotime($month))) . ' 00:00:00');
            $toUnix = strtotime(sprintf('%s-%s-31', date('Y', strtotime($month)), date('m', strtotime($month))) . ' 23:59:59');
            $items[] = ['from' => $fromUnix, 'to' => $toUnix, 'dateFrom' => date('m/d/Y', $fromUnix), 'dateTo' => date('m/d/Y', $fromUnix)];
        }

        $list = [];
        $months = [];
        $userNames = [];
        $saved = [];
        foreach ($items as $item) {
            $records = $this->getDailyBreakDownFromRows($this->getApprUWStatsByDate($item['from'], $item['to']));
            $saved[date('Y-m', $item['from'])] = $records;

            if ($records && count($records) && count($records['list'])) {
                foreach ($records['list'] as $userId => $data) {
                    $userNames[$userId] = $data['user_name'];
                }
            }
        }

        if (isset($userNames) && !empty($userNames)) {
            // Loop over usernames
            foreach ($userNames as $userId => $userName) {
                // Loop over months
                foreach ($saved as $month => $monthData) {
                    if (isset($monthData['list'][$userId])) {
                        $total = $monthData['list'][$userId]['total'];
                    } else {
                        $total = 0;
                    }

                    $list[$userId][$month]['total'] = $total;
                }
            }
        } else {
            foreach ($saved as $month => $monthData) {
                $list[1][$month]['total'] = 0;
            }
        }

        $avg = [];
        foreach ($list as $id => $monthInput) {
            foreach ($monthInput as $monthKey => $item) {
                isset($avg[$monthKey]) ? $avg[$monthKey] += $item['total'] : $avg[$monthKey] = $item['total'];
            }
        }
        $j = "['Month',";

        foreach ($userNames as $userId => $name) {
            $j .= "'" . $name . "',";
        }

        $j .= "'Average'],\n";
        $avg = array_reverse($avg);
        foreach ($avg as $month => $avgValue) {
            $j .= "['" . $month . "',";
            foreach ($userNames as $userId => $name) {
                $j .= "" . intval($list[$userId][$month]['total']) . ",";
            }
            if (isset($userNames) && !empty($userNames)) {
                $j .= "" . floatval(round($avgValue / count($userNames), 2)) . "],\n";
            } else {
                $j .= "" . floatval(round(0, 2)) . "],\n";
            }
        }
        $j = trim($j, ',');
        $data['j'] = $j;
        $data['userNames'] = $userNames;

        return $data;
    }

    /**
     * Return order address
     * @return string
     */
    public function getOrderAddress($row)
    {
        $address = ucwords(trim(strtolower($row->propaddress1) . ' ' . strtolower($row->propaddress2))) . ', ' . ucwords(strtolower($row->propcity)) . ', ' . strtoupper($row->propstate);
        return trim(trim($address), ',');
    }

    /**
     * Return Appr UW Stats Result Title
     * @return string
     */
    public function getApprUWStatsResultTitle($row)
    {
        if ($row->first_approved) {
            return 'First Approved';
        } elseif ($row->sent_back) {
            return 'Corrections Requested';
        } elseif ($row->second_approved) {
            return 'Second Approved';
        }
        return 'N/A';
    }

    /**
     * Remove Commas
     * @return string
     */
    public function removeCommas($t, $encode = false)
    {
        $t = str_replace(array(',', '"', ';'), array(' ', '', ''), $t);
        $t = preg_replace("/\n/", '', $t);
        if ($encode) {
            $t = '"' . $t . '"';
        }
        return $t;
    }

    /**
     * Get Pipeline Classes
     * @return string
     */
    public function getPipelineClasses($row)
    {
        if (!$row) {
            return null;
        }

        $classes = '';

        if ($row->record_is_saved) {
            $classes = ' row-is-saved active';
        }

        if ($row->record_is_hold) {
            $classes = ' row-is-hold danger';
        }

        if ($row->record_is_cu_risk_hold) {
            $classes = ' row-is-cu-risk-hold danger';
        }

        if ($row->is_rush) {
            $classes = ' row-is-rush success';
        }

        if ($row->appr_team) {
            $classes .= sprintf(' row-team-color-%s', $row->appr_team);
        }
        return $classes;
    }

    /**
     * Get Appr UW Time Seconds
     * @return array
     */
    public function getApprUWTimeSeconds($time)
    {
        if (strpos($time, ':') === false) {
            return $time;
        }
        $sec = 0;
        $explode = explode(':', $time);
        // Hours
        $sec += floor($explode[0] * 60 * 60);
        // Min
        $sec += floor($explode[1] * 60);
        // Seconds
        $sec += $explode[2];

        return $sec;
    }

    /**
     * View Checklist
     * @param $id
     * @return array
     */
    public function viewChecklist($id)
    {
        $data = [];
        $record = UW::where('order_id', $id)->first();

        if (!$record) {
            $data['error'] = 'Sorry, That UW record was not found.';
            return $data;
        }
        $orderId = $record->order_id;
        $order = $this->order->select(
            'appr_order.*',
            'type.form as type_form',
            \DB::raw('SUM(end_time-start_time) as total'),
            'type.descrip as type_descrip',
            'lp.descrip as loanpurpose_descrip',
            'au.title as api_user_title',
            'os.descrip as order_status_descrip',
            'client.descrip as client_descrip'
        )
            ->leftJoin('appr_type as type', 'type.id', '=', 'appr_order.appr_type')
            ->leftJoin('loanpurpose as lp', 'lp.id', '=', 'appr_order.loanpurpose')
            ->leftJoin('api_user as au', 'au.id', '=', 'appr_order.api_user')
            ->leftJoin('order_status as os', 'os.id', '=', 'appr_order.status')
            ->leftJoin('user_groups as client', 'client.id', '=', 'appr_order.groupid')
            ->leftJoin('user_group_lender as lender', 'lender.id', '=', 'appr_order.lender_id')
            ->leftJoin('appr_uw_qc_log', 'appr_uw_qc_log.order_id', '=', 'appr_order.id')
            ->leftJoin('appr_order_files', 'appr_order_files.order_id', '=', 'appr_order.id')
            ->where(function ($where) {
                $where->where(\DB::raw('appr_order_files.revision = appr_order.revision and is_final_report = 1'))->orWhere('is_final_report', 1)->orderBy('id', 'ASC');
            })
            ->where('appr_order.id', $orderId)
            ->first();

        if (!$order) {
            $data['error'] = 'Sorry, That Order record was not found.';
            return $data;
        }
        $order->address = $this->getOrderAddress($order);
        $order->group_descrip = $order->client_descrip ?: 'N/A';
        $typeName = Type::select('form', 'descrip')->where('id', $order->appr_type)->first();
        $order->type_name = $typeName ? $typeName->form . ' ' . $typeName->descrip : 'N/A';
        $loanType = LoanReason::where('id', $order->loanpurpose)->first();
        $order->loan_reason = $loanType;
        $order->loan_type = $loanType ? $loanType->descrip : 'N/A';
        $order->appr_name = $this->getApprName($order);
        $order->appr_email = $this->getApprEmail($order);
        $order->qc_type = $this->qcType($order->qc_type);
        $order->previous_report = $this->getOrderFinalAppraisalPDFByRevision($order->id, $order->revision);
        $order->final_appraisal = $this->getOrderFinalAWSReport($order);

        $paymentStatus = strtolower($this->getOrderPaymentStatus($order));
        $order->appointment_schedule = $this->getScheduledAppointmentsMessage($order->id);

        // Make sure it's paid in full
        if (in_array($paymentStatus, ['unpaid'])) {
            $data['error'] = 'This file is locked and can not be reviewed until it is paid in full by the client. Please advise the operations ASAP to collect payment.';
            return $data;
        }

        if ($paymentStatus == 'balance due' && !$this->allowPartialPayment($order->groupid)) {
            $data['error'] = 'This file is locked and can not be reviewed until it is paid in full by the client. Please advise the operations ASAP to collect payment.';
            return $data;
        }
        // Mark as locked
        $data['final_appraisal'] = $order->final_appraisal;
        $data['order_files'] = $this->getOrderFiles($order->id);
        $this->markApprUWLocked($record->id);
        $data['record'] = $record;
        $data['order'] = $order;
        $data['notes'] = $this->notes($order);
        $data['totalTime'] = $this->getTotalTimeSpent($order->id);
        $data['admins'] = $this->getAdmins();
        $data['pendingConditions'] = $this->getPendingConditions($record->id);
        $data['generalChecklist'] = $this->getGeneralChecklist($record, $order);
        $data['UCDPUnits'] = $this->getUCDPUnitsList();
        $data['EADUnits'] = $this->getEADUnitsList();
        $data['realView'] = $this->getRealViewOrder($order->id, $order->revision);
        $data['pdfReport'] = $this->getRealViewFullPDFReport($order->id);
        $data['pdfSummaryReport'] = $this->getRealViewPDFReport($order->id);
        $data['xmlReport'] = $this->getRealViewPDFReport($order->id);
        $data['htmlReport'] = $this->getRealViewPDFReport($order->id);
        $data['scores'] = $this->getRealViewScores($order->id);
        $data['selectedUnit'] = $this->getAppraisalOrderBusinessUnit($order);
        $data['businessUnitsList'] = $this->listData($data['UCDPUnits'], 'unit_id', 'title');
        $data['docFileId'] = $this->getUCDPOrderRecord($order->id);
        $data['fnm_doc'] = $this->getUCDPFNMReport($order->id);
        $data['fre_doc'] = $this->getUCDPFREReport($order->id);
        $data['EADSubmissions'] = $this->getEADOrderRecord($order->id);
        $data['fhLender_id'] = $this->getAppraisalOrderEADFHALenderId($order);
        $data['finalReportXML'] = $this->getOrderFinalAWSReportXML($order);
        $data['appraiser_email_content'] = $this->getAppraiserSettingContent('qc_client_send_back_message');
        $data['isFinalAppraisedValueRequired'] = $this->isFinalAppraisedValueRequired($order);
        $data['qcDataCollection'] = $this->getDataQuestions($order);
        $data['reviewer_activity'] = $this->getReviewerActivity($order, $record);
        return $data;
    }

    /**
     * @param $data
     * @param $id
     * @return bool
     */
    public function storeApprPipeline($data, $id)
    {
        $record = UW::where('id', $id)->first();
        if (!$record) {
            return false;
        }

        $orderId = $record->order_id;
        $order = $this->order->where('id', $orderId)->first();

        if (!$order) {
            return false;
        }

        UW::where('id', $record->id)->update([
            'locked_by' => getUserId(),
            'locked_date' => time()
        ]);

        if ($data['start_time']) {
            $data['start_time'] = time();
        }

        if ($data['rules']) {
            $this->saveConditions($record->id, $data);
        }

        if ($data['general']) {
            $this->saveGeneralChecklist($record->id, $data);
        }

        $orderUpdate = [];
        if ($data['submit'] === 'mark_approved_and_send') {
            $orderUpdate['final_appraised_value'] = $data['final_appraised_value'];
            $this->markApproved($data['id'], ['order' => $order, 'record' => $record, 'post' => $data], true);
        } elseif ($data['submit'] === 'mark_approved') {
            $orderUpdate['final_appraised_value'] = $data['final_appraised_value'];
            $this->markApproved($data['id'], ['order' => $order, 'record' => $record, 'post' => $data]);
        } elseif ($data['submit'] === 'send_back') {
            $this->sendBack($data['id'], ['order' => $order, 'record' => $record, 'post' => $data]);
        } elseif ($data['submit'] === 'save' || $data['submit'] === 'SaveRedirect') {
            $this->save($data['id'], ['order' => $order, 'record' => $record, 'post' => $data]);
        } elseif ($data['submit'] === 'on_hold') {
            $this->onHold($data['id'], ['order' => $order, 'record' => $record, 'post' => $data]);
        } elseif ($data['submit'] === 'cu_risk_hold') {
            $this->cuRiskHold($data['id'], ['order' => $order, 'record' => $record, 'post' => $data]);
        }

        //Save order info
        if ($orderUpdate) {
            OrderFunctionsService::saveAppraiserOrder($orderId, $orderUpdate);
        }

        UW::where('id', $record->id)->update(['locked_by' => 0, 'locked_date' => 0]);

        return true;
    }

    /**
     * @param $id
     * @param array $data
     * @param bool $send
     * @return bool
     */
    public function markApproved($id, $data = [], $send = false)
    {
        $order = $data['order'];
        $record = $data['record'];

        $emailMessage = $this->getAppraiserSettingContent('appr_uw_send_approved_to_client');
        $emailMessage = convertOrderKeysToValues($emailMessage, $order);

        if (isset($data['post']['collection'])) {
            $this->saveAnswers($order->id, $data['post']['collection']);
        }

        if ($send) {
            $emailName = getUserFullNameById($order->orderedby);
            $emailAddress = getUserEmailById($order->orderedby);
            $emailSubject = sprintf(
                $this->getAppraiserSettingContent('company_name') .
                " - %s - UW Conditions Complete - %s - %s",
                $order->id,
                ucwords(strtolower($order->propaddress1)),
                ucwords(strtolower($order->borrower))
            );
            //TODO Client email
        }

        ApprUwQcLog::insert([
            'order_id' => $order->id,
            'uw_id' => $record->id,
            'created_date' => time(),
            'created_userid' => getUserId(),
            'time_taken' => (time() - $data['post']['start_time']),
            'is_approved' => 1,
            'start_time' => $data['post']['start_time'],
            'end_time' => time(),
        ]);

        $this->addStatsRecord($record->id, $order->id, (time() - $data['post']['start_time']), true);

        OrderFunctionsService::saveAppraiserOrder($order->id, ['status' => $this->order::STATUS_UW_CONDITION]);

        $this->markPublicOrderDocuments($order->id);

        ApprUwConditions::where('uw_id', $record->id)->update([
            'is_complete' => 1
        ]);

        UW::where('id', $record->id)->update([
            'is_complete' => 1
        ]);

        if ($order->is_mercury) {
            //TODO Mercury Order
        }

        if ($order->is_valutrac) {
            //TODO Valutrac Order
        }

        if ($order->is_fnc) {
            //TODO FNC Order
        }

        $updates = ['status' => $this->order::STATUS_APPRAISAL_COMPLETED];
        if (!$order->date_uw_complated) {
            $updates['dates_uw_completed'] = date('Y-m-d H:i:s');
        }
        OrderFunctionsService::saveAppraiserOrder($order->id, $updates);
        OrderFunctionsService::markThirdPartyDocumentsVisiblePostCompletion($order);

        return true;
    }

    /**
     * @param $orderId
     * @param $collections
     */
    public function saveAnswers($orderId, $collections)
    {
        foreach ($collections as $collectionId => $answer) {
            $exists = $this->getAnswer($collectionId, $orderId);
            $answer = is_array($answer) ? implode(',', $answer) : $answer;
            try {
                if ($exists) {
                    DataAnswer::where('question_id', $collectionId)->where('order_id', $orderId)
                        ->update([
                            'value' => $answer
                    ]);
                } else {
                    DataAnswer::insert([
                        'value' => $answer,
                        'question_id' => $collectionId,
                        'order_id' => $orderId
                    ]);
                }
            } catch (\Exception $e) {
                $e->getMessage();
            }
        }
    }

    /**
     * @param $id
     * @param null $data
     * @return bool
     */
    public function sendBack($id, $data = null)
    {
        $order = $data['order'];
        $record = $data['record'];

        ApprUwQcLog::insert([
            'order_id' => $order->id,
            'uw_id' => $record->id,
            'created_date' => time(),
            'created_userid' => getUserId(),
            'time_taken' => (time() - $data['post']['start_time']),
            'is_approved' => 0,
            'start_time' => $data['post']['start_time'],
            'end_time' => time(),
        ]);

        $this->addStatsRecord($record->id, $order->id, (time() - $data['post']['start_time']));
        OrderFunctionsService::saveAppraiserOrder($order->id, ['uw_assigned_to' => $data['post']['uw_assigned_to']]);
        OrderFunctionsService::saveAppraiserOrder($order->id, ['status' => $this->order::STATUS_UW_CONDITION]);

        // Email Appraiser
        $emailName = $data['post']['appraiser_name'];
        $emailAddress = $data['post']['appraiser_email'];
        $emailSubject = $data['post']['appraiser_subject'];
        $emailMessage = convertOrderKeysToValues($data['post']['appraiser_email_content'], null);

        if ($emailAddress) {
            //TODO Client email
        }

        return true;
    }

    /**
     * @param $id
     * @param null $data
     */
    public function save($id, $data = null)
    {
        $order = $data['order'];
        $record = $data['record'];

        ApprUwQcLog::insert([
            'order_id' => $order->id,
            'uw_id' => $record->id,
            'created_date' => time(),
            'created_userid' => getUserId(),
            'time_taken' => (time() - $data['post']['start_time']),
            'is_approved' => 0,
            'start_time' => $data['post']['start_time'],
            'end_time' => time(),
        ]);

        OrderFunctionsService::saveAppraiserOrder($order->id, ['uw_assigned_to' => $data['post']['uw_assigned_to']]);

        UW::where('id', $record->id)->update([
            'is_saved' => 1
        ]);
    }

    /**
     * @param $id
     * @param array $data
     */
    public function onHold($id, $data = [])
    {
        $order = $data['order'];
        $record = $data['record'];

        ApprUwQcLog::insert([
            'order_id' => $order->id,
            'uw_id' => $record->id,
            'created_date' => time(),
            'created_userid' => getUserId(),
            'time_taken' => (time() - $data['post']['start_time']),
            'is_approved' => 0,
            'start_time' => $data['post']['start_time'],
            'end_time' => time(),
        ]);

        OrderFunctionsService::saveAppraiserOrder($order->id, ['uw_assigned_to' => $data['post']['uw_assigned_to']]);

        UW::where('id', $record->id)->update([
            'is_hold' => 1
        ]);
    }

    /**
     * @param $id
     * @param array $data
     */
    public function cuRiskHold($id, $data = [])
    {
        $order = $data['order'];
        $record = $data['record'];

        ApprUwQcLog::insert([
            'order_id' => $order->id,
            'uw_id' => $record->id,
            'created_date' => time(),
            'created_userid' => getUserId(),
            'time_taken' => (time() - $data['post']['start_time']),
            'is_approved' => 0,
            'start_time' => $data['post']['start_time'],
            'end_time' => time(),
        ]);

        OrderFunctionsService::saveAppraiserOrder($order->id, ['uw_assigned_to' => $data['post']['uw_assigned_to']]);

        UW::where('id', $record->id)->update([
            'is_cu_risk_hold' => 1
        ]);
    }

    /**
     * @param $orderId
     * @param $collections
     */
    public function saveAnswer($orderId, $collections)
    {
        foreach ($collections as $collectionId => $answer) {
            $exists = DataAnswer::select('value')->where('question_id', $collectionId)->where('order_id', $orderId)->get();
            $answer = is_array($answer) ? implode(',', $answer) : $answer;
            if ($exists) {
                DataAnswer::where('question_id', $collectionId)->where('order_id', $orderId)
                    ->update(['value' => $answer]);
            } else {
                DataAnswer::insert(['value' => $answer, 'question_id' => $collectionId, 'order_id' => $orderId]);
            }
        }
    }

    /**
     * @param $uwId
     * @param $orderId
     * @param $time
     * @param bool $approved
     */
    private function addStatsRecord($uwId, $orderId, $time, $approved = false)
    {
        if ($approved) {
            $exists = ApprUwStats::select('id')->where('uw_id', $uwId)
                ->where('sent_back', 1)->where('order_id', $orderId)->first();
            if ($exists) {
                $extra = ['second_approved' => 1];
            } else {
                $extra = ['first_approved' => 1];
            }
        } else {
            $extra = ['sent_back' => 1];
        }

        ApprUwStats::insert([
            'uw_id' => $uwId,
            'order_id' => $orderId,
            'created_by' => getUserId(),
            'created_date' => time(),
            'time_taken' => $time,
        ] + $extra);
    }

    /**
     * @param $recordId
     * @param $checklist
     */
    public function saveManualChecklistQuestions($recordId, $checklist)
    {
        if ($checklist) {
            QcAnswer::where('qc_id', $recordId)->delete();

            foreach ($checklist as $id => $value) {
                QcAnswer::insert(['selection' => $value, 'qc_id' => $recordId, 'qc_question_id' => $id, 'created_by' => getUserId(), 'created_date' => time()]);

                // If action is required then save history
                if ($value === 'Y') {
                    QcAnswerHistory::insert(['selection' => $value, 'qc_id' => $recordId, 'qc_question_id' => $id, 'created_by' => getUserId(), 'created_date' => time()]);
                }
            }
        }
    }

    /**
     * @param $corrections
     */
    public function saveCustomCorrections($corrections)
    {
        if ($corrections) {
            foreach ($corrections as $id => $data) {
                if ($data['text'] === '') {
                    ApprQcOrderCorrections::where('id', $id)->delete();
                }

                ApprQcOrderCorrections::where('id', $id)
                    ->update(['selection' => $data['selection'], 'title' => $data['text'], 'correction' => $data['text']]);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getApprQcOrderRecord($id)
    {
        return ApprQc::select('appr_qc.*', \DB::raw("CONCAT(o.firstname,' ',o.lastname) as locked_by_name"))
            ->leftjoin('user_data as o', 'o.user_id', '=', 'appr_qc.locked_by')
            ->where('appr_qc.order_id', $id)->where('appr_qc.is_complete', 0)->first();
    }

    /**
     * @param $data
     * @return array
     */
    public function submitRealView($data)
    {
        $type = $data['type'];
        $order = $this->order->where('id', $data['id'])->first();
        $allowedTypes = $this->getRealViewTypes();
        if (!isset($allowedTypes[$type])) {
            return [sprinf("RealView Type %s is not valid", $type), 500];
        }

        $canSubmit = $this->canSubmitRealView($type);
        if ($canSubmit) {
            return [sprinf("Sorry, You are not allowed to submit to a %s RealView Type Report", ucwords($type)), 500];
        }

        try {
            $this->submitForQC($order, $type);
        } catch (\Exception $e) {
            return [$e->getMessage(), 500];
        }

        $realView = $this->getRealViewOrder($order->id, $order->revision);

        return ['html' => 'RealView Submission Completed Successfully.', 'realView' => $realView, 200];
    }

    /**
     * @param $type
     * @return bool
     */
    private function canSubmitRealView($type)
    {
        if ($type === 'gold') {
            return $this->checkPerm('can_submit_realview_gold');
        } elseif ($type === 'platinum') {
            return $this->checkPerm('can_submit_realview_platinum');
        }

        return true;
    }

    /**
     * @param $key
     * @return bool
     */
    public function checkPerm($key)
    {
        return $this->checkPermission(getUserId(), $key);
    }

    /**
     * @param $userId
     * @param $key
     * @return bool
     */
    private function checkPermission($userId, $key)
    {
        $user = userInfo($userId, true);
        $groupId = $user->admin_group;

        $groupPermission = AdminGroupPermission::getGroupPermById($groupId, $key);

        $userPermission = UserPermissions::getUserPermById($userId, $key);

        $access = false;

        if (ClientPermissions::getClientPermission(getUserId(), $key) !== null && !ClientPermissions::getClientPermission(getUserId(), $key)) {
            return $access;
        }

        if ($groupPermission !== false) {
            $access = $groupPermission;
        } else {
            $access = $this->getGroupPermDefaultValue($key);
        }

        // User perm
        if ($userPermission !== false) {
            $access = $userPermission;
        } else {
            if ($access===false) {
                $access = $this->getUserPermDefaultValue($key);
            }
        }

        return (bool) $access;
    }

    /**
     * @param $recordId
     * @param $post
     */
    private function saveConditions($recordId, $post)
    {
        if ($post['rules']) {
            ApprUwAnswers::where('uw_id', $recordId)->delete();

            foreach ($post['rules'] as $id => $value) {
                ApprUwAnswers::insert([
                    'location' => isset($post['rulesconditions'][$id]) ? $post['rulesconditions'][$id] : '',
                    'selection' => $value,
                    'uw_id' => $recordId,
                    'uw_condition_id' => $id,
                    'created_by' => getUserId(),
                    'created_date' => time()
                    ]);
            }
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function getGroupPermDefaultValue($key)
    {
        $perms = AdminPermissionCategory::getAdminGroupPermissions();
        $match = false;
        foreach ($perms as $perm) {
            if ($perm['groups'] && count($perm['groups'])) {
                foreach ($perm['groups'] as $group) {
                    if ($group['items'] && count($group['items'])) {
                        foreach ($group['items'] as $item) {
                            if ($item['key'] == $key) {
                                return $item['default'];
                            }
                        }
                    }
                }
            }
        }

        return $match;
    }

    /**
     * @param $key
     * @return bool
     */
    public function getUserPermDefaultValue($key)
    {
        $perms = AdminPermissionCategory::getAdminGroupPermissions();
        $match = false;
        foreach ($perms as $perm) {
            if ($perm['groups'] && count($perm['groups'])) {
                foreach ($perm['groups'] as $group) {
                    if ($group['items'] && count($group['items'])) {
                        foreach ($group['items'] as $item) {
                            if ($item['key'] == $key) {
                                return $item['default'];
                            }
                        }
                    }
                }
            }
        }

        return $match;
    }

    /**
     * @param $order
     * @param null $type
     * @return bool
     */
    public function submitForQC($order, $type = null)
    {
        $document = OrderFunctionsService::getOrderFinalAWSReportXML($order->id);
        if ($document) {
            return false;
        }

        if ($this->isSubmitAllowed($order)) {
            return false;
        }

        if ($order->qc_type === 'bypass') {
            return false;
        }

        $realViewType = 'basic';

        if ($order->groupid) {
            $group = Client::where('id', $order->groupid)->first();
            if ($group && $group->realview_checklist) {
                if (isset($this->getRealViewTypes()[$group->realview_checklist])) {
                    $realViewType = $group->realview_checklist;
                }
            }
        }

        $lastRealView = $this->getLastRealView($order->id);
        if ($lastRealView && $lastRealView->realview_type) {
            $realViewType = $lastRealView->realview_type;
        }

        if ($type && isset($this->getRealViewTypes()[$type])) {
            $realViewType = $type;
        }

        ApprQcRealViewOrder::isnert([
            'order_id' => $order->id,
            'revision' => $order->revision,
            'created_date' => time(),
            'created_by' => getUserOrSuper(),
            'realview_type' => $realViewType
        ]);

        ApprQcRealViewOrderActivity::isnert([
            'order_id' => $order->id,
            'message' => sprintf('Order Submitted For QC'),
            'created_date' => time(), 'created_by' => getUserOrSuper()
        ]);

        OrderFunctionsService::saveAppraiserOrder($order->id, ['qc_type' => 'realviewhtml']);
    }

    /**
     * @param $recordId
     * @param $post
     */
    private function saveGeneralChecklist($recordId, $post)
    {
        if ($post['general']) {
            ApprUwGeneralAnswers::where('uw_id', $recordId)->delete();

            foreach ($post['general'] as $id => $value) {
                ApprUwGeneralAnswers::insert([
                    'selection' => $value,
                    'uw_id' => $recordId,
                    'question_id' => preg_replace('/[^0-9]/', '', $id)
                ]);
            }
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    private function getLastRealView($id)
    {
        return ApprQcRealViewOrder::where('order_id', $id)->orderBy('created_date', 'desc')->first();
    }

    /**
     * @return array
     */
    private function getRealViewTypes()
    {
        return [
            'basic' => 'RealView Basic',
            'gold' => 'RealView Gold',
            'platinum' => 'RealView Platinum',
        ];
    }

    /**
     * @param $orderId
     * @return bool
     */
    private function getQCClientsCanSkipNotRequired($orderId)
    {
        $setting = $this->getAppraiserSettingContent('qc_not_required_clients_skip');
        if (!$setting) {
            return false;
        }

        $order = $this->order->where('id', $orderId)->first();
        $clientId = $this->user->select('groupid')->where('id', intval($order->orderedby))->toArray();

        return in_array($clientId, explode(',', $setting)) ? true : false;
    }

    /**
     * @param $orderId
     * @return mixed
     */
    private function wasSentBack($orderId)
    {
        return QCReport::select('id')->where('order_id', $orderId)->where('sent_back', 1)->first();
    }

    /**
     * Return Scheduled Appointments Message
     */
    private function getScheduledAppointmentsMessage($id)
    {
        $rows = AppointmentSchedule::where('order_id', $id)->orderBy('id', 'ASC')->get();
        $message = '<ul>';
        if (!count($rows)) {
            $message .= '<li>No Appointments Scheduled.</li>';
        } else {
            foreach ($rows as $row) {
                $message .= sprintf("<li>%s</li>", date('m/d/Y G:i A', $row->appointment_date));
            }
        }
        $message .= '</ul>';
        return $message;
    }

    /**
     * @param $order
     * @param $record
     * @return mixed
     */
    private function getReviewerActivity($order, $record)
    {
        return ApprQcLog::where('order_id', $order->id)->where('qc_id', $record->id)->get();
    }

    /**
     * Return list of UCDP units based on order options
     */
    private function getUCDPUnitsList()
    {
        $list = [];
        $query = UCDPUnit::query();
        $query = $query->select('ucdp_unit.*', 'fnm.ssn_id as fnm_ssn_id', 'fre.ssn_id as fre_ssn_id')
                        ->leftJoin('ucdp_unit_fnm_ssn as fnm', 'ucdp_unit.id', '=', 'fnm.rel_id')
                        ->leftJoin('ucdp_unit_fre_ssn as fre', 'ucdp_unit.id', '=', 'fre.rel_id')
                        ->where('ucdp_unit.is_active', 1)->orderBy('title', 'ASC');
        $units = $query->get();
        if ($units) {
            foreach ($units as $unit) {
                $list[$unit->unit_id] = [
                    'unit_id' => $unit->unit_id,
                    'title' => $unit->title,
                    'fnm_active' => (bool)$unit->fnm_active,
                    'fre_active' => (bool)$unit->fre_active,
                    'fnm' => $unit->fnm_ssn_id,
                    'fre' => $unit->fre_ssn_id,
                ];
            }
        }
        return $list;
    }

    /**
     * Return list of EAD units based on order options
     */
    private function getEADUnitsList()
    {
        $list = [];
        $units = Unit::where('is_active', 1)->orderBy('title', 'ASC')->get();

        if ($units) {
            foreach ($units as $unit) {
                $list[$unit->unit_id] = [
                    'unit_id' => $unit->unit_id,
                    'title' => $unit->title
                ];
            }
        }

        return $list;
    }

    /**
     * Return the real view order
     */
    public function getRealViewOrder($id, $revision)
    {
        return ApprQcRealViewOrder::where('order_id', $id)->where('revision', $revision)->orderBy('created_date', 'DESC')->first();
    }

    /**
     * @param $orderId
     */
    public function markPublicOrderDocuments($orderId)
    {
        // Mark all final reports as hidden first
        OrderFile::where('order_id', $orderId)->where(function ($q) {
            $q->where('is_final_report', 1)->orWhere('is_xml', 1);
        })->update(['is_client_visible' => 0]);

        // Mark the latest pdf as public
        OrderFile::where('order_id', $orderId)->where('is_final_report', 1)
            ->update(['is_client_visible' => 1]);

        // Mark the latest xml as public
        OrderFile::where('order_id', $orderId)->where('is_xml', 1)
            ->update(['is_client_visible' => 1]);
    }

    /**
     * @param $qcId
     * @param $orderId
     * @param $time
     * @param bool $approved
     * @param bool $isSaved
     * @param null $authorId
     */
    public function addQCStatRecord($qcId, $orderId, $time, $approved = false, $isSaved = false, $authorId = null)
    {
        if ($approved) {
            $exists = QCReport::where('qc_id', $qcId)->where('order_id', $orderId)
                ->where('sent_back', 1)->get();
            if ($exists) {
                QCReport::insert([
                    'qc_id' => $qcId,
                    'order_id' => $orderId,
                    'created_by' => $authorId ?: getUserId(),
                    'created_date' => time(),
                    'second_approved' => 1,
                    'time_taken' => $time,
                ]);
            } else {
                QCReport::insert([
                    'qc_id' => $qcId,
                    'order_id' => $orderId,
                    'created_by' => $authorId ?: getUserId(),
                    'created_date' => time(),
                    'first_approved' => 1,
                    'time_taken' => $time,
                ]);
            }
        } else {
            if ($isSaved) {
                QCReport::insert([
                    'qc_id' => $qcId,
                    'order_id' => $orderId,
                    'created_by' => $authorId ?: getUserId(),
                    'created_date' => time(),
                    'first_approved' => 1,
                    'time_taken' => $time,
                ]);
            } else {
                QCReport::insert([
                    'qc_id' => $qcId,
                    'order_id' => $orderId,
                    'created_by' => $authorId ?: getUserId(),
                    'created_date' => time(),
                    'sent_back' => 1,
                    'time_taken' => $time,
                ]);
            }
        }
    }


    /**
     * Check if we need to show the real view submission table
     */
    private function getRealViewSubmission($order)
    {
        if (!$this->isSubmitAllowed($order)) {
            return false;
        }

        if ($order->groupid) {
            $group = Client::teams($order->groupid);
            if ($group && $group->realview_checklist) {
                return true;
            }
        }

        // return true for anything else
        return true;
    }

    /**
     * Check if we are allowed to submit to realview
     * @param  [type]  $order [description]
     * @return boolean        [description]
     */
    private function isSubmitAllowed($order)
    {
        if (Setting::getSetting('realview_allowed_appr_type') && in_array($order->appr_type, explode(',', Setting::getSetting('realview_allowed_appr_type')))) {
            return true;
        }
        return false;
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getAppraiserSettingContent($key)
    {
        $settings = Setting::where('setting_key', $key)->orderBy('id', 'asc')->first();
        return $this->getSettingsValue($settings);
    }

    /**
     * @return bool
     */
    public function isTeamLead()
    {
        $user = userInfo(getUserId());
        return $user->admin_priv && in_array($user->admin_priv, ['S', 'T']) ? true : false;
    }

    public function getUWIgnored()
    {
        $rows = ApprUwCategory::where('is_ignored', 1)->get();
        $list = [];
        foreach ($rows as $row) {
            $list[] = $row->key;
        }
        return $list;
    }

    /**
     * @param $settings
     * @return mixed
     */
    private function getSettingsValue($settings)
    {
        return $settings->value !== "" ? $settings->value : $settings->default_value;
    }

    /**
     * @param $order
     * @return array
     */
    private function getDataQuestions($order)
    {
        $rows = DataQuestion::select('appr_qc_data_collection_question.*', 'answer.value as answer_value')
            ->leftJoin('appr_qc_data_collection_answer as answer', function ($join) use ($order) {
                $join->on('answer.question_id', '=', 'appr_qc_data_collection_question.id')->where('order_id', $order->id);
            })
            ->where('appr_qc_data_collection_question.is_active', 1)->orderBy('appr_qc_data_collection_question.pos', 'asc')->get();
        
        $items = [];

        if (!$rows) {
            return [];
        }

        // Loop each item and check if we have relations selected
        foreach ($rows as $row) {
            // Did we selected client
            if ($order->groupid && ($clients = DataQuestionClient::select('client_id')->where('question_id', $row->id)->get()->toArray()) && !in_array($order->groupid, $clients)) {
                continue;
            }
            if ($order->lender_id && ($lenders = DataQuestionLender::select('lender_id')->where('question_id', $row->id)->get()->toArray()) && !in_array($order->lender_id, $lenders)) {
                continue;
            }
            if ($order->appr_type && ($apprTypes = DataQuestionAppr::select('type_id')->where('question_id', $row->id)->get()->toArray()) && !in_array($order->apprTypes, $apprTypes)) {
                continue;
            }
            if ($order->loantype && ($loanTypes = DataQuestionLoan::select('type_id')->where('question_id', $row->id)->get()->toArray()) && !in_array($order->loanTypes, $loanTypes)) {
                continue;
            }
            if ($order->loanpurpose && ($loanReasons = DataQuestionLoanReason::select('type_id')->where('question_id', $row->id)->get()->toArray()) && !in_array($order->loanReasons, $loanReasons)) {
                continue;
            }
            $row->hasList = $this->getExtraOptions($row->field_extra);
            $items[$row->id] = $row;
        }
        return $items;
    }

    private function getExtraOptions($extra)
    {
        $list = [];

        $temp = trim($extra);

        if (!$temp) {
            return $list;
        }

        switch ($temp) {
            case '#clients#':
                $list = listData($this->getClients(), 'id', 'descrip');
                break;

            case '#appr_types#':
                $list = $this->getApprTypeList();
                break;

            case '#states#':
                $list = getStates();
                break;

            case '#appr_statuses#':
                $list = listData($this->getOrderStatuses(), 'id', 'descrip');
                break;

            case '#loan_purposes#':
                $list = listData($this->getLoanPurposes(), 'id', 'descrip');
                break;

            case '#time_zones#':
                $list = getTimeZoneList();
                break;
        }

        if (!$list && !count($list)) {
            $explode = explode("\r\n", $temp);
            if ($explode && count($explode)) {
                foreach ($explode as $item) {
                    $split = explode("=", $item);
                    $k = '';
                    $v = '';
                    if (count($split) >= 2) {
                        if ($split[0]) {
                            $k = $split[0];
                        }

                        if ($split[1]) {
                            $v = $split[1];
                        }
                    } else {
                        $k = $item;
                        $v = $item;
                    }

                    $list[$k] = $v;
                }
            }
        }

        return $list;
    }

    /**
     * @param $order
     */
    public function sendClientCompletedNotification($order)
    {
        //TODO Client Email;
    }

    /**
     * @return mixed
     */
    private function getClients()
    {
        return Client::orderBy('descrip', 'asc')->get();
    }

    /**
     * @return array
     */
    private function getApprTypeList()
    {
        $rows = Type::orderBy(\DB::raw("CONCAT(form,'',descrip)"), 'asc');
        $types = [];

        foreach ($rows as $row) {
            $types[$row->id] = $row->form ? ($row->form . ' - ' . $row->descrip) : $row->descrip;
        }

        return $types;
    }

    /**
     * @return mixed
     */
    private function getOrderStatuses()
    {
        return Status::orderBy('descrip', 'asc')->get();
    }

    private function getLoanPurposes()
    {
        return LoanReason::orderby('descrip', 'asc')->get();
    }

    /**
     * @param $orderId
     * @return array
     */
    public function getOrderFiles($orderId)
    {
        $rows = [];
        $docs = OrderFile::where('order_id', $orderId)->where('is_vendor', 0)->where('is_client_visible', 1)->orderBy('created_at', 'desc')->get();

        if ($docs and count($docs) > 0) {
            foreach ($docs as $d) {
                if ($d->is_aws) {
                    $filePath = null;
                    $fileLocation = null;
                    $error = false;
                    /*try {
                        $object = AmazonS3Wrapper::getObject(AWS_S3_BUCKET . '/appraisals/' . $orderId, $d->filename);
                    } catch(Exception $e) {
                        $error = true;
                    }*/
                    if (!$error) {
//                        $size = $object['ContentLength'];
//                        $size /= 1024;
//                        $size = round($size,2);
                        $fileFullName = explode('.', $d->filename);
                        $rows[] = array(
                            'id' => $d->id,
                            'ext' => end($fileFullName),
                            'name' => $d->docname,
                            'size' => 10000,
                            'filename' => urlencode($d->filename),
                            'file' => rawurlencode($orderId . "_" . $d->filename),
                            'location' => $d->file_location,
                            'fileLocation' => $fileLocation,
                            'filePath' => $filePath,
                            'created_at' => $d->created_at,
                            'created_by' => $d->created_by,
                            'is_client_visible' => $d->is_client_visible,
                            'is_appr_visible' => $d->is_appr_visible,
                            'is_borrower_visible' => $d->is_borrower_visible,
                            'is_aws' => 1,
                            'document_type' => $d->document_type,
                            'date' => '2018.06.15'

                        );
                    }
                } else {
                    $filePath = BP . "/order_docs/" . $orderId . "_" . $d->filename;
                    $fileLocation = "/order_docs/" . $orderId . "_" . $d->filename;
                    if ($d->file_location) {
                        $fileLocation = "/order_docs/" . $d->file_location . '/' . $d->filename;
                        $filePath = BP . "/order_docs/" . $d->file_location . '/' . $d->filename;
                    }

                    if (file_exists($filePath)) {
                        $size = filesize($filePath);
                        $size /= 1024;
                        $size = round($size, 2);
                        $fileFullName = explode('.', $d->filename);
                        $rows[] = array(
                            'id' => $d->id,
                            'ext' => end($fileFullName),
                            'name' => $d->docname,
                            'size' => $size,
                            'filename' => urlencode($d->filename),
                            'file' => rawurlencode($orderId . "_" . $d->filename),
                            'location' => $d->file_location,
                            'fileLocation' => $fileLocation,
                            'filePath' => $filePath,
                            'created_at' => $d->created_at,
                            'created_by' => $d->created_by,
                            'is_client_visible' => $d->is_client_visible,
                            'is_appr_visible' => $d->is_appr_visible,
                            'date' => '2018.06.15'
                        );
                    }
                }
            }
        }

        return $rows;
    }

    /**
     * @param $order
     * @return bool
     */
    private function isFinalAppraisedValueRequired($order)
    {
        $types = $this->getAppraiserSettingContent('final_appraised_value_appr_types');

        if (!$types) {
            return false;
        }

        // Make sure it's in one of the values
        if (in_array($order->appr_type, explode(',', $types))) {
            return true;
        }

        return false;
    }

    /**
     * @param $order
     * @return bool
     */
    private function getEADSubmission($order)
    {
        if ($order->groupid || $order->lender_id) {
            $rows = [];
            $exists = $this->eadUnitClientRel->select('client_id', 'rel_id')
                ->leftJoin('ead_unit', 'id', '=', 'rel_id')
                ->where('is_active', 1)
                ->where('client_id', $order->groupid);

            if ($exists) {
                $rows = $exists->get();
            }
            if (!$rows && $order->lender_id > 0) {
                $exists = $this->eadUnitLenderRel->select('lender_id', 'rel_id')
                    ->leftJoin('ead_unit', 'id', '=', 'rel_id')
                    ->where('is_active', 1)
                    ->where('lender_id', $order->lender_id);
                if ($exists) {
                    $rows = $exists->get();
                }
            }
            if ($rows) {
                foreach ($rows as $row) {
                    // Load the appraisal types and loan types
                    $appraisalTypes = $this->eadUnitAppraisalTypeRel->select('appr_id')
                        ->leftJoin('ead_unit', 'id', '=', 'rel_id')
                        ->where('is_active', 1)
                        ->where('rel_id', $row->rel_id)->get();
                    $loanTypes = $this->eadUnitLoanTypeRel->select('loan_id')
                        ->leftJoin('ead_unit', 'id', '=', 'rel_id')
                        ->where('is_active', 1)
                        ->where('rel_id', $row->rel_id)->get();
                    // As soon as we see one then we return true
                    if (!$appraisalTypes && !$loanTypes) {
                        return true;
                    }
                    if ($appraisalTypes && !in_array($order->appr_type, $this->listData($appraisalTypes, 'appr_id', 'appr_id'))) {
                        continue;
                    }
                    // Check if loan type is set
                    if ($loanTypes && !in_array($order->loantype, $this->listData($loanTypes, 'loan_id', 'loan_id'))) {
                        continue;
                    }

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param $order
     * @return bool
     */
    private function getUCDPSubmission($order)
    {
        if ($order->groupid || $order->lender_id) {
            $rows = [];
            $exists = $this->ucdpUnitClientRel->select('client_id', 'rel_id')
                ->leftJoin('ucdp_unit', 'id', '=', 'rel_id')
                ->where('is_active', 1)
                ->where('client_id', $order->groupid)->get();
            if ($exists) {
                $rows = $exists;
            }

            if (!$rows && $order->lender_id > 0) {
                $exists = $this->ucdpUnitLenderRel->select('lender_id', 'rel_id')
                    ->leftJoin('ucdp_unit', 'id', '=', 'rel_id')
                    ->where('is_active', 1)
                    ->where('lender_id', $order->lender_id)->get();
                if ($exists) {
                    $rows = $exists;
                }
            }

            if ($rows) {
                foreach ($rows as $row) {
                    // Load the appraisal types and loan types
                    $appraisalTypes = $this->ucdpUnitAppraisalTypeRel->select('appr_id')
                        ->leftJoin('ucdp_unit', 'id', '=', 'rel_id')
                        ->where('is_active', 1)
                        ->where('rel_id', $row->rel_id)->get();
                    $loanTypes = $this->ucdpUnitLoanTypeRel->select('loan_id')
                        ->leftJoin('ucdp_unit', 'id', '=', 'rel_id')
                        ->where('is_active', 1)
                        ->where('rel_id', $row->rel_id)->get();

                    // As soon as we see one then we return true
                    if (!$appraisalTypes && !$loanTypes) {
                        return true;
                    }

                    // Check if appraisal type is set
                    if ($appraisalTypes && !in_array($order->appr_type, $this->listData($appraisalTypes, 'appr_id', 'appr_id'))) {
                        continue;
                    }

                    // Check if loan type is set
                    if ($loanTypes && !in_array($order->loantype, $this->listData($loanTypes, 'loan_id', 'loan_id'))) {
                        continue;
                    }

                    // If we reached this far
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param $arr
     * @param $key
     * @param $value
     * @return array
     */
    private function listData($arr, $key, $value)
    {
        $list = [];
        // Do we need more then one column for the value? if it has . in it then we do
        $findValues = [];
        if (strpos($value, '.') !== false) {
            $findValues = explode('.', $value);
        }
        if ($arr && count($arr)) {
            foreach ($arr as $k => $values) {
                if (!is_null($values)) {
                    $values = (array)$values;
                    if (!is_null($findValues)) {
                        $valueName = '';
                        foreach ($findValues as $findValue) {
                            if (isset($values[$findValue])) {
                                $valueName .= $values[$findValue] . ' ';
                            }
                        }

                        if (isset($values[$key]) && $valueName) {
                            $list[$values[$key]] = trim($valueName);
                        }
                    } else {
                        if (isset($values[$key]) && isset($values[$value])) {
                            $list[$values[$key]] = $values[$value];
                        }
                    }
                } else {
                    if (count($findValues)) {
                        $valueName = '';
                        foreach ($findValues as $findValue) {
                            if (isset($arr[$findValue])) {
                                $valueName .= $arr[$findValue] . ' ';
                            }
                        }

                        if (isset($arr[$key]) && $valueName) {
                            $list[$arr[$key]] = trim($valueName);
                        }
                    } else {
                        if (isset($arr[$key]) && isset($arr[$value])) {
                            $list[$arr[$key]] = $arr[$value];
                        }
                    }
                }
            }
        }
        return $list;
    }

    /**
     * @param $id
     */
    public function getOrderById($id)
    {
        $query = $this->order->where('id', $id);
        $row = $query->first();
        $row->address = $this->getOrderAddress($row);
        return $row;
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function getUwConditions($orderId)
    {
        $order = $this->order->select('appr_order.*', 'client.descrip as client_descrip')
            ->leftJoin('user_groups as client', 'client.id', '=', 'appr_order.groupid')
            ->where('appr_order.id', $orderId)->first();
        $order->group_descrip = $order->client_descrip ?: 'N/A';
        $typeName = Type::select('form', 'descrip')->where('id', $order->appr_type)->first();
        $order->type_name = $typeName ? $typeName->form . ' ' . $typeName->descrip : 'N/A';
        $loanType = LoanReason::where('id', $order->loanpurpose)->first();
        $order->loan_type = $loanType ? $loanType->descrip : 'N/A';
        $order->appr_name = $this->getApprName($order);
        $order->appr_email = $this->getApprEmail($order);


        return $order;
    }

    /**
     * @param $orderId
     * @return array
     */
    public function getApprUWPreviousConditions($orderId)
    {
        $rows = $this->appUw->select('appr_uw_conditions.*')
            ->leftJoin('appr_uw', 'appr_uw_conditions.uw_id', '=', 'appr_uw.id')
            ->where('appr_uw.order_id', $orderId)
            ->get();
        $list = [];
        if($rows) {
            foreach ($rows as $row) {
                $answer = ApprUwAnswers::where('uw_condition_id', $row->id)->first();
                if($answer && $row->is_complete === 1) {
                    $category = ApprUwCategory::where('key', $row->category)->first();
                    $row->category = $category->title;
                    $list[] = $row;
                }
            }
        }
        return $list;
    }

    /**
     * @param $orderId
     * @return mixed
     */
    public function getRecordByOrderId($orderId)
    {
        return UW::where('order_id', $orderId)->where('is_complete', 0)->first();
    }

    /**
     * @param $record
     * @return null
     */
    public function getUWOrderContacts($record)
    {
        if($record) {
            return ApprUwContacts::where('uw_id', $record->id)->get();
        }
        return null;
    }

    /**
     * @param $orderId
     * @return array
     */
    public function getUWPendingConditionsByOrderId($orderId)
    {
        $rows = $this->appUw->select('appr_uw_conditions.*', 'uw_category.is_ignored')
            ->leftJoin('appr_uw', 'appr_uw.id', '=', 'appr_uw_conditions.uw_id')
            ->leftjoin('uw_category', 'appr_uw_conditions.category', '=', 'uw_category.key')
            ->where('appr_uw.order_id', $orderId)
            ->where('appr_uw.is_complete', 0)->get();
        $list = [];
        if ($rows) {
            foreach ($rows as $row) {
                $answer = ApprUwAnswers::where('uw_condition_id', $row->id)->first();
                if ($answer && strtolower($answer->selection) === 'n') {
                    continue;
                }
                $list[] = $row;
            }
        }
        return $list;
    }

    /**
     * @return mixed
     */
    public function getApprUWCategories()
    {
        return ApprUwCategory::orderBy('title', 'asc')->get();
    }

    /**
     * @param $data
     * @param $orderId
     * @return bool
     */
    public function storeConditions($data, $orderId)
    {
        $supportEmails = isset($data['send_support_emails']) ? $data['send_support_emails'] : 0;
        $reportEmails = isset($data['send_final_report_emails']) ? $data['send_final_report_emails'] : 0;
        $sendToClient = isset($data['send_to_client']) ? $data['send_to_client'] : 0;
        $sendToAppr = isset($data['send_to_appr']) ? $data['send_to_appr'] : 0;

        $conditionText = isset($data['condition_text']) ? $data['condition_text'] : [];
        $conditionCategory = isset($data['condition_category']) ? $data['condition_category'] : null;
        $conditionResponse = isset($data['condition_response']) ? $data['condition_response'] : null;

        $contactNames = isset($data['contact_name']) ? $data['contact_name'] : null;
        $contactEmails = isset($data['contact_email']) ? $data['contact_email'] : null;
        $record = $this->getRecordByOrderId($orderId);
        $order = $this->getOrderById($orderId);
        if (!$record) {
            // Create record
            UW::create([
                'order_id' => $orderId,
                'created_by' => getUserId(),
                'created_date' => Carbon::now()->timestamp,
                'send_support_emails' => $supportEmails,
                'send_final_report_emails' => $reportEmails,
                'send_to_client' => $sendToClient,
                'send_to_appr' => $sendToAppr,
            ]);
            $record = $this->getRecordByOrderId($orderId);
        } else {
            // Update record
            UW::where('id', $record->id)->update([
                'send_support_emails' => $supportEmails,
                'send_final_report_emails' => $reportEmails,
                'send_to_client' => $sendToClient,
                'send_to_appr' => $sendToAppr,
                'created_date' => Carbon::now()->timestamp,
            ]);
        }
        $this->appUw->where('uw_id', $record->id)->where('is_complete', 0)->delete();

        // Insert conditions
        if ($conditionText && count($conditionText)) {
            foreach ($conditionText as $i => $text) {
                $category = $conditionCategory[$i];
                $response = $conditionResponse[$i];
                $newUWCondition = $this->appUw->create([
                    'uw_id' => $record->id,
                    'created_by' => getUserId(),
                    'created_date' => Carbon::now()->timestamp,
                    'category' => $category,
                    'response' => $response,
                    'is_approved' => 1,
                    'cond' => $text,
                ]);
                ApprUwAnswers::where('uw_id', $record->id)->where('uw_condition_id', $i)->update([
                    'uw_condition_id' => $newUWCondition->id
                ]);
            }
        }

        ApprUwContacts::where('uw_id', $record->id)->delete();

        // Insert contacts
        if ($contactNames && count($contactNames)) {
            foreach ($contactNames as $i => $name) {
                $email = $contactEmails[$i];
                ApprUwContacts::create([
                    'uw_id' => $record->id,
                    'created_by' => getUserId(),
                    'created_date' => Carbon::now()->timestamp,
                    'name' => $name,
                    'email' => $email,
                ]);
            }
        }

        $updates = ['status' => $this->order::STATUS_UW_CONDITION];
        if (!$order->date_uw_received) {
            $updates['date_uw_received'] = Carbon::now()->timestamp;
        }

        OrderFunctionsService::saveAppraiserOrder($order->id, $updates);

        if ($sendToAppr) {
            $emailName = $data['email_name'];
            $emailAddress = $data['email_email'];
            $emailSubject = $data['email_subject'];
            $emailMessage = $data['email_message'];

            $emailMessage = convertOrderKeysToValues($emailMessage, $order);

            $filesIncluded = "";
            if (isset($data['attach']) && count($data['attach'])) {
                $filesIncluded = "<br /><i>Files included with this email:</i><br /><span style='font-family:sans-serif;font-size:8pt;'>";
                foreach ($data['attach'] as $filePath => $fileName) {
                    $filesIncluded .= $fileName . '<br />';
                }
                $filesIncluded .= "</span>";
            }
            $emailMessage .= $filesIncluded;
            //TODO appraiser email
        }

        if ($sendToClient) {
            $conditionRows = $this->appUw->where('uw_id', $record->id)->get();
            $conditions = '';
            if ($conditionRows && count($conditionRows)) {
                foreach ($conditionRows as $cond) {
                    $conditions .= "<li>" . $cond->cond . "</li>\n";
                }
            }
            $emailName = getUserFullNameById($order->orderedby);
            $emailAddres = getUserEmailById($order->orderedby);
            $emailSubject = sprintf($this->getAppraiserSettingContent('company_name') . " - %s - UW Conditions - %s - %s", $order->id, ucwords(strtolower($order->propaddress1)), ucwords(strtolower($order->borrower)));

            $emailMessage = $this->getAppraiserSettingContent('appr_uw_send_uw_to_client');
            $emailMessage = convertOrderKeysToValues($emailMessage, $order);

            //TODO appraiser email
        }

        return true;
    }

    /**
     * @param $orderId
     * @return bool
     */
    public function destroyUWConditions($orderId, $data)
    {
        $order = $this->getOrderById($orderId);
        $conditions = $this->getUWPendingConditionsByOrderId($orderId);
        $record = $this->getRecordByOrderId($orderId);

        $supportEmails = isset($data['send_support_emails']) ? $data['send_support_emails'] : 0;
        $reportEmails = isset($data['send_final_report_emails']) ? $data['send_final_report_emails'] : 0;
        $sendToClient = isset($data['send_to_client']) ? $data['send_to_client'] : 0;
        $sendToAppr = isset($data['send_to_appr']) ? $data['send_to_appr'] : 0;

        if (!$record) {
            UW::create([
                'order_id' => $orderId,
                'created_by' => getUserId(),
                'created_date' => Carbon::now()->timestamp,
                'send_support_emails' => $supportEmails,
                'send_final_report_emails' => $reportEmails,
                'send_to_client' => $sendToClient,
                'send_to_appr' => $sendToAppr,
            ]);
            $record = $this->getRecordByOrderId($orderId);
        }
        $this->appUw->where('uw_id', $record->id)->where('is_complete', 0)->delete();

        OrderFunctionsService::saveAppraiserOrder($order->id, ['status' => $this->order::STATUS_APPRAISAL_COMPLETED]);

        UW::where('id', $record->id)->delete();

        $list = "<ol/>";
        foreach ($conditions as $condition) {
            $list .= "<li>" . convertOrderKeysToValues($condition->cond, $order) . "</li>";
        }
        $list .= "</ol>";
        $content = sprintf("Removed All Conditions. <Br /> %s <br /> Status Changed to Appraisal Completed.", $list);
        //TODO QuickLogEntry

        return true;
    }

    /**
     * Return general checklist items
     *
     */
    private function getGeneralChecklist($record, $order)
    {
        $query = $this->checklist->select('appr_uw_checklist.id', 'appr_uw_checklist.title', 'appr_uw_checklist.correction', 'c.title as category_title', 'g.selection as answer')
            ->leftJoin('appr_uw_general_answers as g', function ($join) use ($record) {
                $join->on('appr_uw_checklist.id', '=', 'g.question_id')->where('g.uw_id', '=', $record->id);
            })
            ->leftJoin('appr_uw_checklist_category as c', 'c.id', '=', 'appr_uw_checklist.category_id')
            ->leftJoin('appr_uw_checklist_appr_type_rel as a', 'appr_uw_checklist.id', '=', 'a.rel_id')
            ->leftJoin('appr_uw_checklist_loan_type_rel as l', 'appr_uw_checklist.id', '=', 'l.rel_id')
            ->leftJoin('appr_uw_checklist_loan_reason_rel as r', 'appr_uw_checklist.id', '=', 'r.rel_id')
            ->leftJoin('appr_uw_checklist_client_rel as client', 'appr_uw_checklist.id', '=', 'client.rel_id')
            ->leftJoin('appr_uw_checklist_lender_rel as lender', 'appr_uw_checklist.id', '=', 'lender.rel_id')
            ->where('c.is_active', 1)->where('appr_uw_checklist.is_active', 1)
            ->where(function ($q) use ($order) {
                $q->where('a.local_id', $order->appr_type)->orWhereNull('a.local_id');
            })
            ->where(function ($q) use ($order) {
                $q->where('l.local_id', $order->loantype)->orWhereNull('l.local_id');
            })
            ->where(function ($q) use ($order) {
                $q->where('r.local_id', $order->loanpurpose)->orWhereNull('r.local_id');
            })
            ->where(function ($q) use ($order) {
                $q->where('client.local_id', $order->groupid)->orWhereNull('client.local_id');
            })
            ->where(function ($q) use ($order) {
                $q->where('lender.local_id', $order->lender_id)->orWhereNull('lender.local_id');
            })
            ->groupBy('appr_uw_checklist.id')->orderBy('category_title', 'ASC');
        $rows = $query->get();

        $list = [];
        foreach ($rows as $row) {
            $list[$row['category_title']][$row['id']] = $row;
        }

        if ($list) {
            $list = (object)$list;
        }
        return $list;
    }

    /**
     * Return pending conditions
     */
    public function getPendingConditions($recordId)
    {
        $query = $this->appUw->select('appr_uw_conditions.*', 'a.selection as answer', 'a.location')
            ->leftJoin('appr_uw_answers as a', 'a.uw_condition_id', '=', 'appr_uw_conditions.id')
            ->leftJoin('uw_category as uc', 'appr_uw_conditions.category', '=', 'uc.key')
            ->where('appr_uw_conditions.uw_id', $recordId)->where('uc.is_ignored', 0);
        return $query->orderBy('appr_uw_conditions.created_date', 'ASC')->get();
    }

    /**
     * Return all Admins
     */
    private function getAdmins()
    {
        $rows = $this->user->select('user.id', 'user.email', \DB::raw("LOWER(TRIM(CONCAT(data.firstname,' ',data.lastname))) as firstname_lastname"))
            ->leftJoin('user_data as data', 'user.id', '=', 'data.user_id')
            ->where('user_type', 1)
            ->where('active', 'Y')
            ->where('show_in_assign', 1)
            ->orderBy('firstname_lastname', 'ASC')->get();
        $users = [];
        foreach ($rows as $row) {
            $users[$row->id] = ucwords($row->firstname_lastname);
        }
        return $users;
    }

    /**
     * Return total time spent
     */
    private function getTotalTimeSpent($id)
    {
        $row = ApprUwQcLog::select(\DB::raw('SUM(end_time-start_time) as total'))->where('order_id', $id)->first();
        return $row->total;
    }

    /**
     * Return notes
     */
    private function notes($order)
    {
        $notes = [];
        if ($order->lender_id) {
            // Get lender record
            $lenderTitle = $this->getLenderTitleById($order->lender_id);
            // Get emails
            $lenders = $this->getWholeSaleLenderByOrderId($order);
            if ($lenders) {
                $notes[] = sprintf("Final Report will also be delivered to '%s' on this file at: %s", $lenderTitle, implode(', ', explode("\n", $lenders)));
            }
        }

        $orderNotes = ApprOrderNotes::where('orderid', $order->id)->where('type', 'uw')->orderBy('created_date', 'DESC')->get();
        if ($orderNotes) {
            foreach ($orderNotes as $note) {
                $notes[] = $note->note;
            }
        }
        return $notes;
    }

    /**
     * Return Whole Sale Lender By Order Id
     */
    private function getWholeSaleLenderByOrderId($order, $type = 'qc')
    {
        $group = $this->getGroupByUserId($order->orderedby);
        if (!$group) {
            return false;
        }
        // See if we need to send emails for this group
        if (!$group->lender_final_email) {
            return false;
        }
        // Get lender by id
        $lender = UserGroupLender::where('id', $order->lender_id)->first();

        if (!$lender) {
            return false;
        }
        // Do we need to send emails
        if (!$lender->send_final_report) {
            return false;
        }
        if ($type == 'uw') {
            // Do we have any emails
            if (!$lender->final_report_emails_uw) {
                return false;
            }
            return $lender->final_report_emails_uw;
        } else {
            // Do we have any emails
            if (!$lender->final_report_emails) {
                return false;
            }
            return $lender->final_report_emails;
        }
    }

    /**
     * Return Group By User Id
     */
    private function getGroupByUserId($userId)
    {
        $user = $this->user->select('groupid')->where('id', $userId)->first();
        $group = null;
        if ($user && $user->groupid) {
            $group = Client::where('id', $user->groupid)->first();
        }
        return $group ? $group : false;
    }


    /**
     * Return Lender Title By Id
     */
    private function getLenderTitleById($id)
    {
        $record = UserGroupLender::where('id', $id)->first();
        return $record ? $record->lender : 'N/A';
    }

    /**
     * Return Order Final Appraisal PDF By Revision
     */
    private function getOrderFinalAppraisalPDFByRevision($orderId, $revision)
    {
        return OrderFile::where('order_id', $orderId)->where('revision', $revision)->where('is_final_report', 1)->first();
    }

    /**
     * Return Order Final AWS Report
     */
    private function getOrderFinalAWSReport($order)
    {
        if ($order->revision) {
            $row = OrderFile::where('order_id', $order->id)->where('revision', $order->revision)->where('is_final_report', 1)->first();
        } else {
            $row = OrderFile::where('order_id', $order->id)->where('is_final_report', 1)->orderBy('id', 'ASC')->first();
        }
        return $row;
    }

    /**
     * Return order qc type
     */
    private function qcType($qcType)
    {
        switch ($qcType) {
            case "manual":
                return "Manual Checklist";
                break;
            case "realview":
                return "RealView Checklist (Landscape)";
                break;
            case "realviewhtml":
                return "RealView Checklist HTML (RealView)";
                break;
            case "bypass":
                return "Bypass Checklist";
                break;
        }
    }

    /**
     * Return appr amc
     */
    private function getAppr($order)
    {
        if (!$order->is_assigned) {
            return false;
        }

        if ($order->amc_id) {
            return Amc::where('id', $order->amc_id)->first();
        } elseif ($order->acceptedby) {
            return userInfo($order->acceptedby, true);
        }
        return false;
    }

    /**
     * Return appr name
     */
    private function getApprName($order)
    {
        $appr = $this->getAppr($order);
        if ($order->amc_id) {
            return $appr ? $appr->title : '';
        } elseif ($order->acceptedby) {
            return $appr ? $appr->firstname . ' ' . $appr->lastname : '';
        }
        return '';
    }

    private function getApprEmail($order)
    {
        $appr = $this->getAppr($order);
        if ($order->amc_id) {
            return $appr ? $appr->outgoing_email : '';
        } elseif ($order->acceptedby) {
            return $appr ? $appr->email : '';
        }
        return '';
    }

    /**
     * Return order payment status
     */
    private function getOrderPaymentStatus($order)
    {
        if ($order->is_cod == 'Y' && $order->billmelater != 'Y' && !$order->is_check_payment && $order->paid_amount <= $order->invoicedue) {
            return 'COD';
        } elseif ($order->is_collect_from_borrower) {
            return 'Collect From Borrower';
        } elseif ($order->paid_amount > $order->invoicedue) {
            return 'Refund Due';
        } elseif ($order->refund_date) {
            return 'Refunded';
        } elseif ($order->is_order_paid || ($order->paid_amount > 0 && $order->invoicedue && $order->paid_amount >= $order->invoicedue)) {
            return 'Paid';
        } elseif ($order->billmelater == "Y" || $order->is_check_payment) {
            return 'Invoiced';
        } elseif ($order->paid_amount > 0 and $order->paid_amount < $order->invoicedue) {
            return 'Balance Due';
        } else {
            return 'Unpaid';
        }
    }

    /**
     * Mark as locked
     */
    private function markApprUWLocked($id)
    {
        UW::where('id', $id)->update([
            'locked_by' => getUserId(),
            'locked_date' => time()
        ]);
    }

    /**
     * Check if group can have partial payments
     *
     */
    private function allowPartialPayment($groupId)
    {
        $group = Client::where('id', $groupId)->first();

        if (!$group) {
            return false;
        }
        return $group->allow_partial_payment == 'Y';
    }
}
