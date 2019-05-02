<?php

namespace Modules\Admin\Http\Controllers\PostCompletionPipelines;

use Session;
use Carbon\Carbon;
use App\Models\Users\User;
use Illuminate\Http\Request;
use App\Models\Clients\Client;
use App\Models\Appraisal\Order;
use Modules\Admin\Services\OrderFunctionsService;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\PostCompletionPipelines\ApprUwPipelineRepository;

class ApprUwPipelineController extends AdminBaseController
{
    /**
    * Index
    * @return view
    */
    public function index(ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $teams = $apprUwPipelineRepository->getTeams();
        $awaitingApprovalCount = $apprUwPipelineRepository->getAppraisalUWRecords(Order::STATUS_UW_APPROVAL, false, true);
        $pendingCorrectionsCount = $apprUwPipelineRepository->getAppraisalUWRecords(Order::STATUS_UW_CONDITION, false, true);
        $awaitingApprovalView = $this->awaitingApprovalView($apprUwPipelineRepository, $teams);
        $pendingCorrectionsView = $this->pendingCorrectionsView($apprUwPipelineRepository, $teams);


        return view(
            'admin::post-completion-pipelines.appr-uw-pipeline.index',
            compact(
                'teams',
                'awaitingApprovalView',
                'pendingCorrectionsView',
                'awaitingApprovalCount',
                'pendingCorrectionsCount',
                'awaitingApprovals'
            )
        );
    }

    /**
    * Awaiting Approval View
    * @return view
    */
    public function awaitingApprovalView($apprUwPipelineRepository, $teams)
    {
        return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._awaiting_approval', compact('teams'));
    }

    /**
    * Pending Corrections View
    * @return view
    */
    public function pendingCorrectionsView($apprUwPipelineRepository, $teams)
    {
        return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._pending_corrections', compact('teams'));
    }

    /**
    * Data for Awaiting Approval Tab
    * @return view
    */
    public function dataAwaitingApproval(ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        return $apprUwPipelineRepository->dataAwaitingApproval();
    }

    /**
    * Data for Pending Corrections Tab
    * @return view
    */
    public function dataPendingCorrections(ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        return $apprUwPipelineRepository->dataPendingCorrections();
    }

    /**
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function statistics(ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        return view('admin::post-completion-pipelines.appr-uw-pipeline.statistics');
    }

    /**
    * Data for Pending Corrections Tab
    * @return view
    */
    public function getStatistics(Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $inputs = $request->all();
        $from = $inputs['date_from'];
        $to = $inputs['date_to'];

        if (!$from || !$to) {
            Session::flash('error', 'Please select both dates');
            return redirect()->back();
        }

        // Convert to unix
        $fromUnix = Carbon::parse($from)->timestamp;
        $toUnix = Carbon::parse($to)->timestamp;

        // Grab stats from appr_uw_stats
        $stats = [];
        $appUwStatsByDate = $apprUwPipelineRepository->getApprUWStatsByDate($fromUnix, $toUnix);
        $daily = $apprUwPipelineRepository->getDailyBreakDownFromRows($appUwStatsByDate);
        $conditionsOrders = $apprUwPipelineRepository->getApprUWOrdersHavingConditions($fromUnix, $toUnix);
        $conditions = $apprUwPipelineRepository->getApprUWOrdersConditions($fromUnix, $toUnix);

        // Add to stats
        $stats['orders_having_conditions'] = $conditionsOrders->total;
        $stats['total_conditions'] = $conditions->total;
        $stats['avg_conditions_per_order'] = $conditionsOrders->total ? round(($conditions->total / $conditionsOrders->total), 2) : 0;

        $conditionUsers = $apprUwPipelineRepository->getApprUWConditionUsers($fromUnix, $toUnix);
        $from = $fromUnix;
        $to = $toUnix;

        $guageChartsView = $this->guageChartsView($stats);
        $dailyPieChartView = $this->dailyPieChartView($daily);
        $dailyComboChartView = $this->dailyComboChartView($apprUwPipelineRepository);

        return view(
            'admin::post-completion-pipelines.appr-uw-pipeline.statistics',
                compact(
                    'conditionUsers',
                    'daily',
                    'stats',
                    'from',
                    'to',
                    'guageChartsView',
                    'dailyPieChartView',
                    'dailyComboChartView'
                )
            );
    }

    /**
    * Guage Charts View
    * @return view
    */
    private function guageChartsView($inputs)
    {
        $stats = $inputs;
        return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._daily_gauge_chart', compact('stats'));
    }

    /**
    * Daily Pie Chart View
    * @return view
    */
    private function dailyPieChartView($inputs)
    {
        $daily = $inputs;
        return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._daily_pie_chart', compact('daily'));
    }

    /**
    * Daily Combo Chart View
    * @return view
    */
    private function dailyComboChartView($apprUwPipelineRepository)
    {
        $data = $apprUwPipelineRepository->dailyComboChartData();
        $j = $data['j'];
        $userNames = $data['userNames'];
        return view('admin::post-completion-pipelines.appr-uw-pipeline.partials._daily_combo_chart', compact('j', 'userNames'));
    }


    /**
     * View Checklist
     * @param $id
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function viewChecklist($id, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $rows = $apprUwPipelineRepository->viewChecklist($id);

        if (isset($rows['error'])) {
            Session::flash('error', $rows['error']);
            return redirect()->back();
        }

        $order = $rows['order'];
        $record = $rows['record'];
        $notes = $rows['notes'];
        $totalTime = $rows['totalTime'];
        $admins = $rows['admins'];
        $pendingConditions = $rows['pendingConditions'];
        $generalChecklist = $rows['generalChecklist'];
        $UCDPUnits = $rows['UCDPUnits'];
        $EADUnits = $rows['EADUnits'];
        $realView = $rows['realView'];
        $pdfReport = $rows['pdfReport'];
        $pdfSummaryReport = $rows['pdfSummaryReport'];
        $xmlReport = $rows['xmlReport'];
        $htmlReport = $rows['htmlReport'];
        $scores = $rows['scores'];
        $selectedUnit = $rows['selectedUnit'];
        $businessUnitsList = $rows['businessUnitsList'];
        $selectedFNM = 0;
        $selectedFRE = 0;
        $orderFiles = $rows['order_files'];
        $fnmList = [];
        $freList = [];
        $docFileId = $rows['docFileId'];
        $orderEADSubmission = $rows['EADSubmissions'];
        $finalReportXML = $rows['finalReportXML'];
        $orderUCDPSubmission = $docFileId;
        $FNMDocument = $rows['fnm_doc'];
        $FREDocument = $rows['fre_doc'];
        $fhaLenderId = $rows['fhLender_id'];
        $apprEmailContent = $rows['appraiser_email_content'];
        $reviewerActivity = $rows['reviewer_activity'];
        $isFinalAppraisedValueRequired = $rows['isFinalAppraisedValueRequired'];
        $qcDataCollection = $rows['qcDataCollection'];
        return view(
            'admin::post-completion-pipelines.appr-uw-pipeline.partials.view_checklist._checklist',
                    compact(
                            'order',
                            'record',
                            'notes',
                            'totalTime',
                            'admins',
                            'pendingConditions',
                            'generalChecklist',
                            'UCDPUnits',
                            'EADUnits',
                            'realView',
                            'pdfReport',
                            'pdfSummaryReport',
                            'xmlReport',
                            'htmlReport',
                            'scores',
                            'selectedUnit',
                            'businessUnitsList',
                            'selectedFNM',
                            'selectedFRE',
                            'fnmList',
                            'freList',
                            'docFileId',
                            'finalReportXML',
                            'orderUCDPSubmission',
                            'FNMDocument',
                            'FREDocument',
                            'fhaLenderId',
                            'orderEADSubmission',
                            'orderFiles',
                            'apprEmailContent',
                            'reviewerActivity',
                            'isFinalAppraisedValueRequired',
                            'qcDataCollection'
                        )
                );
    }

    /**
     * @param $id
     * @param Request $request
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePipeline($id, Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $postData = $request->all();
        $store = $apprUwPipelineRepository->storeApprPipeline($postData, $id);
        if ($store) {
            if ($postData['submit'] === 'SaveRedirect') {
                return redirect()->route('admin.post-completion-pipelines.appr-uw-pipeline', ['id' => $postData['id']]);
            }
            return redirect()->route('admin.post-completion-pipelines.appr-uw-pipeline');
        }
    }

    /**
     * @param $id
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function uwConditions($id, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $order = $apprUwPipelineRepository->getUwConditions($id);
        $previousConditions = $apprUwPipelineRepository->getApprUWPreviousConditions($id);
        $record = $apprUwPipelineRepository->getRecordByOrderId($id);
        $contacts = $apprUwPipelineRepository->getUWOrderContacts($record);
        $conditions = $apprUwPipelineRepository->getUWPendingConditionsByOrderId($id);
        $uwCategories = $apprUwPipelineRepository->getApprUWCategories();
        $orderFiles = $apprUwPipelineRepository->getOrderFiles($id);
        $apprEmailContent = $apprUwPipelineRepository->getAppraiserSettingContent('uw_conditions_send_to_appraiser');
        $isTeamLead = $apprUwPipelineRepository->isTeamLead();
        $UWIgnored = $apprUwPipelineRepository->getUWIgnored();
        return view(
            'admin::post-completion-pipelines.appr-uw-pipeline.partials._uw_conditions',
            compact(
                'order',
                'previousConditions',
                'record',
                'contacts',
                'conditions',
                'uwCategories',
                'orderFiles',
                'apprEmailContent',
                'isTeamLead',
                'UWIgnored'
            )
        );
    }

    /**
     * @param $id
     * @param Request $request
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveConditions($id, Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $store = $apprUwPipelineRepository->storeConditions($request->all(), $id);
        if ($store) {
            return redirect()->back();
        }
    }

    public function destroyConditions($id, Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $tmpDestroy = $apprUwPipelineRepository->destroyUWConditions($id, $request->all());
        if ($tmpDestroy) {
            return response()->json(['success' => 1, 'message' => 'All Conditions Removed'], 200);
        }
        return response()->json(['success' => 0, 'message' => 'something is wrong to remove all conditions'], 500);
    }

    /**
     * @param Request $request
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserInfo(Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $inputs = $request->all();
        $from = $inputs['from'];
        $to = $inputs['to'];
        $userId = $inputs['userId'];

        $rows = $apprUwPipelineRepository->getUWStatsByUserId($from, $to, $userId);
        $user = userInfo($userId, $full = true);

        if (!$user) {
            return response()->json(['error' => 'User Was not found.']);
        }

        $title = sprintf('%s (%s)', trim($user->firstname . ' ' . $user->lastname), count($rows));
        $html = \View::make('admin::post-completion-pipelines.appr-uw-pipeline.partials._user_rows', compact('rows'))->render();

        return response()->json(['title' => $title, 'html' => $html]);
    }

    /**
     * @param Request $request
     * @param ApprUwPipelineRepository $apprUwPipelineRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkRealViwSubmit(Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $submited = $apprUwPipelineRepository->submitRealView($request->all());
        return response()->json($submited);
    }

    /**
    * Get User Info
    * @return view
    */
    public function getUserConditionInfo(Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $inputs = $request->all();
        $from = $inputs['from'];
        $to = $inputs['to'];
        $userId = $inputs['userId'];

        $rows = $apprUwPipelineRepository->getUWConditionsStatsByUserId($from, $to, $userId);
        $user = userInfo($userId, $full = true);

        if (!$user) {
            return response()->json(['error' => 'User Was not found.']);
        }

        $title = sprintf('%s (%s)', trim($user->firstname . ' ' . $user->lastname), count($rows));
        $html = \View::make('admin::post-completion-pipelines.appr-uw-pipeline.partials._user_condition_rows', compact('rows'))->render();

        return response()->json(['title' => $title, 'html' => $html]);
    }

    /**
    * Uw Report
    * @return view
    */
    public function uwReport(ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $clients = Client::getAllClients();
        $admins = User::getAdminMembers();

        return view(
            'admin::post-completion-pipelines.appr-uw-pipeline.uw_report',
                compact(
                    'clients',
                    'admins'
                )
        );
    }

    /**
    * Uw Report Download
    * @return file
    */
    public function uwReportDownload(Request $request, ApprUwPipelineRepository $apprUwPipelineRepository)
    {
        $inputs = $request->all();
        $from = $inputs['date_from'];
        $to = $inputs['date_to'];
        $clients = isset($inputs['client']) ? $inputs['client'] : [];
        $type = $inputs['datetype'];
        $user = $inputs['user'];

        // Convert to unix
        $fromUnix = Carbon::parse($from)->timestamp;
        $toUnix = Carbon::parse($to)->timestamp;

        // Get results from the stats method
        $rows = $apprUwPipelineRepository->getUWReportRecords($fromUnix, $toUnix, $type, $clients);

        if (!count($rows)) {
            Session::flash('error', 'No Records Found.');
            return redirect()->back();
        }
        // Create an array of the items
        $items = [];
        foreach ($rows as $row) {
            $r = [];
            foreach ($row as $k => $v) {
                $v = $apprUwPipelineRepository->removeCommas($v);
                $r[$k] = $v;
                if ($k == 'cond' || $k == 'client') {
                    $r[$k] = '"' . str_replace('"', '', $v) . '"';
                }
                // if ($k == 'id') {
                //     $r[$k] = BASE_URL . '/admin/order.php?id=' . $v;
                // }
            }

            $qcApproved = $apprUwPipelineRepository->getQCApprovedByOrderId($row->id);
            $name = $qcApproved ? $qcApproved->name : 'N/A';
            $userId = $qcApproved ? $qcApproved->id : 0;

            if ($user && $userId != $user) {
                continue;
            }
            $extra = ['qc_approved_by' => $apprUwPipelineRepository->removeCommas($name)];
            $items[] = array_merge($extra, $r);
        }
        if (!count($items)) {
            Session::flash('error', 'No Records Found.');
            return redirect()->back();
        }

        // Create content
        $headers = array_keys($items[0]);
        $content = [];

        // Add headers
        $content[] = implode(',', $headers);

        // Add rows
        foreach ($items as $item) {
            $content[] = implode(',', $item);
        }

        // Download file
        $name = sprintf("UW-Report-%s---%s.csv", $from, $to);
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=" . $name);
        header("Pragma: no-cache");
        header("Expires: 0");
        echo implode("\n", $content);
        exit;
    }
}
