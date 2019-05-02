<?php

namespace Modules\Admin\Http\Controllers\PostCompletionPipelines;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Tools\Setting;
use Yajra\Datatables\Datatables;
use Modules\Admin\Repositories\Ticket\OrderRepository;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\PostCompletionPipelines\MailPipelineRepository;
use Modules\Admin\Repositories\Appraisal\DocuVaultOrderRepository;
use App\Models\Appraisal\OrderFile;
use App\Models\DocuVault\OrderFiles as DocuvaultOrderFiles;
use App\Models\Appraisal\MailPipeline\{ SentMail, SentFiles};

class MailPipelineController extends AdminBaseController {

    private $pendingCount;
    private $sentCount;
    private $deliveredCount;

    /**
     * Instantiate a new MailPipelineController instance.
     */
    public function __construct(MailPipelineRepository $mailPipelineRepository)
    {
        $this->pendingCount = $mailPipelineRepository->getMailRecords("isPending",null, true);
        $this->sentCount = $mailPipelineRepository->getMailRecords("isSent",null, true);
        $this->deliveredCount = $mailPipelineRepository->getMailRecords("isDelivered",null, true);
    }

    /**
    * Index
    * @return view
    */
    public function index(
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository
        )
    {
        $pendingView = $this->pendingView();
        $sentView = $this->sentView();
        $deliveredView = $this->deliveredView();
        $pendingCount = $this->pendingCount;
        $sentCount = $this->sentCount;
        $deliveredCount = $this->deliveredCount;

        return view('admin::post-completion-pipelines.mail-pipeline.index',
                compact(
                        'pendingView',
                        'sentView',
                        'deliveredView',
                        'pendingCount',
                        'sentCount',
                        'deliveredCount'
                    )
            );
    }

    /**
    * pending tab
    * @return view
    */
    public function pendingView()
    {
        return view('admin::post-completion-pipelines.mail-pipeline.partials._pending');
    }

    /**
    * pending tab
    * @return json
    */
    public function pendingData(
            Request $request,
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository)
    {
        return $mailPipelineRepository->pendingData($request, $docuVaultOrderRepository, $orderRepository, $this->pendingCount);
    }

    /**
    * sent tab
    * @return view
    */
    public function sentView()
    {
        return view('admin::post-completion-pipelines.mail-pipeline.partials._sent');
    }

    /**
    * sent tab
    * @return json
    */
    public function sentData(
            Request $request,
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository)
    {
        return $mailPipelineRepository->sentData($request, $this->sentCount);
    }

    /**
    * delivered tab
    * @return view
    */
    public function deliveredView()
    {
        return view('admin::post-completion-pipelines.mail-pipeline.partials._delivered');
    }

    /**
    * sent tab
    * @param $request, $mailPipelineRepository
    * @return json
    */
    public function deliveredData(
            Request $request,
            MailPipelineRepository $mailPipelineRepository
        )
    {
        return $mailPipelineRepository->deliveredData($request, $this->deliveredCount);
    }

    /**
    * view Row
    * @param $id, $mailPipelineRepository, $docuVaultOrderRepository, $orderRepository
    * @return view
    */
    public function viewRow($id,
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository
        )
    {
        $icc = null;
        $invoice = null;
        $documents = null;
        $finalAppraisal = null;
        $row = $mailPipelineRepository->getById($id);
        $order = $mailPipelineRepository->getMailOrderRecordByType($row, $docuVaultOrderRepository, $orderRepository);

        if($row->type == 'docuvault') {
            $documents = DocuvaultOrderFiles::getAllOrderDocumentVaultDocuVaultDocuments($order->id);
            $finalAppraisal = DocuvaultOrderFiles::getDocuVaultOrderFinalAppraisalDocument($order->id);
            $invoice = DocuvaultOrderFiles::getDocuVaultOrderInvoiceDocument($order->id);
            $icc = DocuvaultOrderFiles::getDocuVaultOrderICCDocument($order->id);
        } elseif($row->type == 'appr') {
            $documents = OrderFile::getOrderDocumentVaultAllDocuments($order->id);
            $finalAppraisal = OrderFile::getOrderFinalAppraisalDocument($order->id, $orderRepository);
        }

        return view('admin::post-completion-pipelines.mail-pipeline.partials._view_row_modal',
            compact(
                'row',
                'order',
                'documents',
                'finalAppraisal',
                'invoice',
                'icc'
            )
        );
    }

    /**
    * mark Sent Form
    * @param $id, $mailPipelineRepository, $docuVaultOrderRepository, $orderRepository
    * @return view
    */
    public function markSentForm($id,
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository
        )
    {
        $documents = null;
        $finalAppraisal = null;
        $row = $mailPipelineRepository->getById($id);
        $order = $mailPipelineRepository->getMailOrderRecordByType($row, $docuVaultOrderRepository, $orderRepository);
        $hasLabel = $mailPipelineRepository->getLabels($order->id, $row->type);

        if($row->type == 'docuvault') {
            $documents = DocuvaultOrderFiles::getAllOrderDocumentVaultDocuVaultDocuments($order->id);
            $finalAppraisal = DocuvaultOrderFiles::getDocuVaultOrderFinalAppraisalDocument($order->id);
        } elseif($row->type == 'appr') {
            $documents = OrderFile::getOrderDocumentVaultAllDocuments($order->id);
            $finalAppraisal = OrderFile::getOrderFinalAppraisalDocument($order->id, $orderRepository);
        }

        return view('admin::post-completion-pipelines.mail-pipeline.partials._mark_modal',
            compact(
                'row',
                'order',
                'documents',
                'finalAppraisal',
                'hasLabel'
            )
        );
    }

    /**
    * edit Tracking Number
    * @param $id, $mailPipelineRepository
    * @return view
    */
    public function editTrackingNumber($id, MailPipelineRepository $mailPipelineRepository)
    {
        $row = $mailPipelineRepository->getById($id);
        if(!$row) {
			return json_encode(['error' => 'Sorry, that record was not found.']);
        }
        return view('admin::post-completion-pipelines.mail-pipeline.partials._edit_tracking_number_modal', compact('row'));
    }

    /**
    * mark Ready To Mail
    * @param $id, $mailPipelineRepository
    * @return mixed
    */
    public function markReadyToMail($id, MailPipelineRepository $mailPipelineRepository)
    {
        return $mailPipelineRepository->markReadyToMail($id);
    }

    /**
    * do Mark Failed
    * @param $id, $mailPipelineRepository
    * @return mixed
    */
    public function doMarkFailed($id, MailPipelineRepository $mailPipelineRepository)
    {
        return $mailPipelineRepository->doMarkFailed($id);
    }

    /**
    * do Mark Delivered
    * @param $id, $mailPipelineRepository
    * @return mixed
    */
    public function doMarkDelivered($id, MailPipelineRepository $mailPipelineRepository)
    {
        return $mailPipelineRepository->doMarkDelivered($id);
    }

    /**
    * do Save Tracking Number
    * @param $request, $mailPipelineRepository
    * @return mixed
    */
    public function doSaveTrackingNumber(
            Request $request,
            MailPipelineRepository $mailPipelineRepository
        )
    {
        return $mailPipelineRepository->doSaveTrackingNumber($request);
    }

    /**
    * create Label
    * @param $request, $mailPipelineRepository, $docuVaultOrderRepository, $orderRepository
    * @return mixed
    */
    public function createLabel(
            Request $request,
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository
        )
    {
        return $mailPipelineRepository->createLabel($request, $docuVaultOrderRepository, $orderRepository);
    }

    /**
    * do Mark Sent
    * @param $request, $mailPipelineRepository, $docuVaultOrderRepository, $orderRepository
    * @return mixed
    */
    public function doMarkSent(Request $request,
            MailPipelineRepository $mailPipelineRepository,
            DocuVaultOrderRepository $docuVaultOrderRepository,
            OrderRepository $orderRepository
        )
    {
        return $mailPipelineRepository->doMarkSent($request, $docuVaultOrderRepository, $orderRepository);
    }

    /**
    * create Pdf Label
    * @param $request, $mailPipelineRepository
    * @return pdf file
    */
    public function createPdfLabel(
            Request $request,
            MailPipelineRepository $mailPipelineRepository
        )
    {
        return $mailPipelineRepository->createPdfLabel($request);
    }

    /**
    * download Label
    * @param $request, $mailPipelineRepository
    * @return pdf file
    */
    public function downloadLabel(
            Request $request,
            MailPipelineRepository $mailPipelineRepository
        )
    {
        return $mailPipelineRepository->downloadLabel($request);
    }


}
