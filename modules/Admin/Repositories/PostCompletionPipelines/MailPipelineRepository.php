<?php

namespace Modules\Admin\Repositories\PostCompletionPipelines;

use App\Models\Appraisal\MailPipeline\SentMail;
use App\Models\Tools\ShippingItem;
use App\Models\Documents\DocumentType;
use EasyPost\EasyPost;
use App\Models\Tools\Setting;
use App\Models\Tools\ShippingAddress;
use App\Models\Appraisal\MailPipeline\SentFiles;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class MailPipelineRepository
{
    private $mailPipeline;
    private $shippingItem;
    protected $order;
    protected $errors = [];
    protected $hasError = false;
    protected $isSuccess = false;
    protected $shipment;
    protected $label;
    protected $tracker;
    protected $rate;
    protected $rates;
    protected $fees;
    protected $postageLabel;
    protected $rateAmount;
    protected $rateService;
    protected $rateDeliveryDays;
    protected $labelDocument;
    protected $labelImage;
    protected $fromAddress;
    protected $toAddress;
    protected $parcel;

    const SERVICE_PRIORITY = 'Priority';
    const SERVICE_EXPRESS = 'Express';

    const TYPE_APPR = 'appraisal';
    const TYPE_DOCUVAULT = 'docuvault';

    /**
     * LoanTypesRepository constructor.
     */
    public function __construct()
    {
        $this->mailPipeline = new SentMail();
        $this->shippingItem = new ShippingItem();
        $key = Setting::getSetting('appraisal_shipping_test_key');
        if(Setting::getSetting('appraisal_shipping_mode')) {
            $key = Setting::getSetting('appraisal_shipping_prod_key');
        }
        EasyPost::setApiKey($key);
    }

    /**
     * @param $type
     * @return collection
     */
    public function getSendMailEntryById($id)
    {
        return $this->mailPipeline->where('id', $id)->first();
    }

    /**
     * @param $type
     * @return collection
     */
    public function getMailRecords($type, $cond = null, $count = false)
    {
        $query = $this->mailPipeline
                    ->select('appr_sent_mail.*', 'a.borrower as aborrower', 'd.borrower as dborrower')
                    ->leftJoin('appr_order as a', \DB::raw("(`a`.`id` = `appr_sent_mail`.`orderid` and `appr_sent_mail`.`type`"), \DB::raw("'appr')"))
                    ->leftJoin('appr_docuvault_order as d', \DB::raw("(`d`.`id` = `appr_sent_mail`.`orderid` and `appr_sent_mail`.`type`"), \DB::raw("'docuvault')"));

        if ($type === "isPending") {
            $query = $query->where(\DB::raw("( (`appr_sent_mail`.`type` = 'appr' AND `a`.`final_appraisal_borrower_sendtopostalmail` = 'Y') OR (`appr_sent_mail`.`type` = 'docuvault' AND `d`.`status` NOT IN (10,20)) ) AND `appr_sent_mail`.`sent_date`"), 0);
        } elseif ($type === "isSent") {
            $query = $query->where("sent_date", ">", 0)->where("delivered_date", 0)->where("is_failed", 0);
        } elseif ($type === "isDelivered") {
            $query = $query->where("delivered_date", ">", 0);
        }
        if(!is_null($cond) && $cond['search'] != '') {
            $query = $query->where(function ($query) use ($cond) {
                $query->where('appr_sent_mail.orderid', 'like', "%{$cond['search']}%")
                    ->orWhere('appr_sent_mail.type', 'like', "%{$cond['search']}%")
                    ->orWhere('appr_sent_mail.tracking_number', 'like', "%{$cond['search']}%")
                    ->orWhere('a.borrower', 'like', "%{$cond['search']}%")
                    ->orWhere('d.borrower', 'like', "%{$cond['search']}%")
                    ->orWhere(\DB::raw("(CONCAT(a.propaddress1,', ',a.propcity,', ',a.propstate,' ',a.propzip) like '%{$cond["search"]}%' or CONCAT(d.propaddress1,', ',d.propcity,', ',d.propstate,' ',d.propzip)"), 'like',\DB::raw("'%{$cond["search"]}%')"));
            });
        }
        if($count) {
            return $query->orderBy("created_date", "DESC")->count();
        }elseif(!is_null($cond)){
            // dd($query->toSql());
            return $query->offset($cond['start'])->orderBy($cond['order'], $cond['dir'])->with('createdBy', 'sentBy')->limit($cond['limit'])->get();
        }
    }

    /**
     * @param $row, $docuVaultOrderRepository, $orderRepository
     * @return collection
     */
    public function getMailOrderRecordByType($row, $docuVaultOrderRepository, $orderRepository)
    {
        if($row->type == 'docuvault') {
            return $docuVaultOrderRepository->getDocuVaultOrderById($row->orderid);
        }
        return $orderRepository->getApprOrderById($row->orderid);
    }

    /**
     * @param $id
     * @return collection
     */
    public function getById($id)
    {
        return $this->mailPipeline->where('id', $id)->first();
    }

    /**
     * Get Labels
     * @param $id, $type
     * @return collection
     */
    public function getLabels($id, $type = null)
    {
        $query = $this->shippingItem
                    ->select('shipping_item.*',
                        \DB::raw('CONCAT(`fromaddress`.`street1`,", ",`fromaddress`.`city`,", ",`fromaddress`.`state`," ",`fromaddress`.`zip`) as from_address'),
                        \DB::raw('CONCAT(toaddress.street1,", ",toaddress.city,", ",toaddress.state," ",toaddress.zip) as to_address')
                    )
                    ->leftJoin('shipping_address as fromaddress', \DB::raw("(`fromaddress`.`shippment_id` = `shipping_item`.`id` AND `fromaddress`.`address_type`"), \DB::raw("'from')"))
                    ->leftJoin('shipping_address as toaddress', \DB::raw("(`toaddress`.`shippment_id` = `shipping_item`.`id` AND `toaddress`.`address_type`"), \DB::raw("'to')"))
                    ->where('shipping_item.order_id', $id);

        if (!is_null($type) && $type == "appr") {
            $query = $query
                        ->leftJoin('appr_order', \DB::raw("(`appr_order`.`id` = `shipping_item`.`order_id` AND `shipping_item`.`order_type`"), \DB::raw("'appraisal')"))
                        ->where('shipping_item.order_type', 'appraisal');
        }elseif (!is_null($type) && $type == "docuvault") {
            $query = $query
                        ->leftJoin('appr_docuvault_order', \DB::raw("(`appr_docuvault_order`.`id` = `shipping_item`.`order_id` AND `shipping_item`.`order_type`"), \DB::raw("'docuvault')"))
                        ->where('shipping_item.order_type', 'docuvault');
        }

        $row = $query->orderBy("created_date", "DESC")->first();
        return is_null($row) ?: $this->getLabelById($row->id);
    }

    /**
     * Get Label
     * @param $id
     * @return collection
     */
    public function getLabelById($id)
    {
        $row = $this->shippingItem->where('id', $id)->first();
        return $row;
    }

    /**
     * Create Shipment
     * @param $order
     * @return collection
     */
    public function createShipment($order)
    {
        $this->order = $order;
        try {
            $this->shipment = \EasyPost\Shipment::create([
                "to_address" => $this->toAddress(),
                "from_address" => $this->fromAddress(),
                "parcel" => $this->getParcel()
            ]);
                $this->setRates($this->shipment->rates);
            } catch(\EasyPost\Error $e) {
                return $e->getMessage();
            }
            if(isset($this->shipment->id)) {
            // See if there are any rate errors
            if($this->shipment->messages) {
                $this->setHasError(true);
                foreach($this->shipment->messages as $message) {
                $this->setErrors($message->message);
                }
            } else {
                $this->setIsSuccess(true);
            }
        }
        return $this->shipment;
    }

    /**
     * To Address
     * @return collection
     */
    public function toAddress()
    {
        return \EasyPost\Address::create([
            'name' => $this->order->final_appraisal_borrower_name,
            'street1' => $this->order->final_appraisal_borrower_address1,
            'street2' => $this->order->final_appraisal_borrower_address2,
            'city' => $this->order->final_appraisal_borrower_city,
            'state' => $this->order->final_appraisal_borrower_state,
            'zip' => $this->order->final_appraisal_borrower_zip,
            'country' => 'US',
            'phone' => $this->order->borrower_phone,
            'email' => $this->order->borrower_email
        ]);
    }

    /**
     * From Address
     * @return collection
     */
    public function fromAddress()
    {
        return \EasyPost\Address::create([
            'company' => Setting::getSetting('company_name'),
            'street1' => Setting::getSetting('company_address'),
            'street2' => Setting::getSetting('company_address2'),
            'city' => Setting::getSetting('company_city'),
            'state' => Setting::getSetting('company_state'),
            'zip' => Setting::getSetting('company_zip'),
            'country' => 'US',
            'phone' => Setting::getSetting('company_phone'),
            'email' => Setting::getSetting('email_account_help')
        ]);
    }

    /**
     * Buy Label
     * @return label
     */
    public function buyLabel($order, $shippmentId, \EasyPost\Rate $rate=null)
    {
        $this->order = $order;
        try {
            $this->label = \EasyPost\Shipment::retrieve($shippmentId);
            $this->label->buy(['rate' => $rate ?: $this->label->lowest_rate()]);
            $this->label = \EasyPost\Shipment::retrieve($shippmentId);
            $this->label->label(['file_format' => 'PDF']);
            // Set tracker
            $this->setTracker($this->label->tracker);
            $this->setFromAddress($this->label->from_address->__toArray(true));
            $this->setToAddress($this->label->to_address->__toArray(true));
            $this->setParcel($this->label->parcel);
            $this->setRate($this->label->selected_rate);
            $this->setRateAmount($this->getRate()->rate);
            $this->setRateService($this->getRate()->service);
            $this->setRateDeliveryDays($this->getRate()->est_delivery_days ?: $this->getRate()->delivery_days);
            $this->setFees($this->label->fees);
            $fee = 0;
            foreach($this->getFees() as $feeItem) {
                $fee += $feeItem->amount;
            }
            $this->setFee($fee);
            $this->setPostageLabel($this->label->postage_label);
            $this->setLabelDocument($this->getPostageLabel()->label_pdf_url);
            $this->setLabelImage($this->getPostageLabel()->label_url);
            $this->setIsSuccess(true);
        } catch(\EasyPost\Error $e) {
            return $e->getMessage();
        }

        return $this->label;
    }

    /**
    * pending tab
    * @return json
    */
    public function pendingData($request, $docuVaultOrderRepository, $orderRepository, $count)
    {
        $columns = $this->tableColumns();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $pendingData = $this->getMailRecords("isPending", ['limit' => $limit, 'start' => $start, 'order' => $order, 'dir' => $dir, 'search' => $search]);
        $recordsFiltered = $this->getMailRecords("isPending", ['limit' => $limit, 'start' => $start, 'order' => $order, 'dir' => $dir, 'search' => $search], true);
        $data = $this->filterData($pendingData, $docuVaultOrderRepository, $orderRepository);

        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($count),
                    "recordsFiltered" => intval($recordsFiltered),
                    "data"            => $data
                    );

        return json_encode($json_data);
    }

    /**
    * sent tab
    * @param $request
    * @return json
    */
    public function deliveredData($request, $count)
    {
        $columns = $this->tableColumns();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $deliveredData = $this->getMailRecords("isDelivered", ['limit' => $limit, 'start' => $start, 'order' => $order, 'dir' => $dir, 'search' => $search]);
        $recordsFiltered = $this->getMailRecords("isDelivered", ['limit' => $limit, 'start' => $start, 'order' => $order, 'dir' => $dir, 'search' => $search], true);
        $data = $this->filterData($deliveredData);
        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($count),
                    "recordsFiltered" => intval($recordsFiltered),
                    "data"            => $data
                    );

        return json_encode($json_data);
    }

    /**
    * sent tab
    * @return json
    */
    public function sentData($request, $count)
    {
        $columns = $this->tableColumns();
        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        $sentData = $this->getMailRecords("isSent", ['limit' => $limit, 'start' => $start, 'order' => $order, 'dir' => $dir, 'search' => $search]);
        $recordsFiltered = $this->getMailRecords("isSent", ['limit' => $limit, 'start' => $start, 'order' => $order, 'dir' => $dir, 'search' => $search], true);
        $data = $this->filterData($sentData);
        $json_data = array(
                    "draw"            => intval($request->input('draw')),
                    "recordsTotal"    => intval($count),
                    "recordsFiltered" => intval($recordsFiltered),
                    "data"            => $data
                    );

        return json_encode($json_data);
    }

    /**
    * create Label
    * @param $request, $docuVaultOrderRepository, $orderRepository
    * @return mixed
    */
    public function createLabel($request, $docuVaultOrderRepository, $orderRepository)
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        $type = $inputs['type'];
        $row = $this->getSendMailEntryById($id);

        if(!$row) {
            return json_encode(['error' => 'Sorry, that record was not found.']);
        }

        // Load Order info based on the item
        $order = $this->getMailOrderRecordByType($row, $docuVaultOrderRepository, $orderRepository);

        // Create Shippment
        $shippment = $this->createShipment($order);
        if(!$this->isSuccess()) {
            return json_encode(['error' => $this->getMessage()]);
        }

        $rate = $this::SERVICE_PRIORITY;
        if($type == 'express') {
            $rate = $this::SERVICE_EXPRESS;
        }

        // Buy Label
        $label = $this->buyLabel($order, $shippment->id, $this->getServiceRate($rate));
        if(!$this->isSuccess()) {
            return json_encode(['error' => $this->getMessage()]);
        }

        $orderType = $row->type == 'docuvault' ? 'docuvault' : 'appraisal';
        try {
            $this->save($order, $orderType);
        } catch(\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }

        // Save tracking number
        try {
        // Update record
        SentMail::where('id', $row->id)->update(['tracking_number' => $this->getTrackingNumber()]);
        } catch(\Exception $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
        $hasLabel = $this->getLabels($order->id, $row->type);
        return json_encode(['html' => 'OK', 'label' => $label->id, 'labelId' => $hasLabel->id, 'trackingNumber' => $this->getTrackingNumber()]);
    }

    /**
    * do Mark Sent
    * @param $request, $docuVaultOrderRepository, $orderRepository
    * @return mixed
    */
    public function doMarkSent()
    {
        $inputs = $request->all();
        $id = $inputs['id'];
        $row = $this->getSendMailEntryById($id);

		if(!$row) {
			return json_encode(['error' => 'Sorry, that record was not found.']);
        }

		$trackingNumber = trim($inputs['trackingNumber']);
        $files = explode(',', $inputs['files']);

		if(!$trackingNumber) {
			return json_encode(['error' => 'Sorry, you must enter a tracking number.']);
		}
		if(!count($files)) {
			return json_encode(['error' => 'Sorry, you must select at least one document.']);
        }
		try {
            // Update record
            SentMail::where('id', $row->id)->update([
                'tracking_number' => $id = $inputs['trackingNumber'],
                'sent_date' => Carbon::now()->timestamp,
                'sent_by' => getUserId()
            ]);
			// Insert Documents
			foreach($files as $file) {
				$fileId = str_replace('file_', '', $file);
        SentFiles::create([
                    'orderid' => $row->orderid,
                    'fileid' => $fileId,
                    'apprsentid' => $row->id
                ]);
			}
			// Send Notification
			//markSentNotifyClientEmail($row);

		} catch(\Exception $e) {
			return json_encode(['error' => $e->getMessage()]);
		}
		return json_encode(['html' => 'Order Marked Sent.']);
    }

    /**
    * do Save Tracking Number
    * @param $request
    * @return mixed
    */
    public function doSaveTrackingNumber($request)
    {
        $inputs = $request->all();
        $row = $this->getById($inputs['id']);

        if(!$row) {
			return json_encode(['error' => 'Sorry, that record was not found.']);
		}
		$trackingNumber = trim($inputs['trackingNumber']);

		if(!$trackingNumber) {
			return json_encode(['error' => 'Sorry, you must enter a tracking number.']);
		}

		try {
            $row->update(['tracking_number' => $trackingNumber]);
		} catch(\Exception $e) {
			return json_encode(['error' => $e->getMessage()]);
		}
		return json_encode(['html' => 'Tracking Number Updated.']);
    }

    /**
    * create Pdf Label
    * @param $request
    * @return pdf file
    */
    public function createPdfLabel($request)
    {
        $ids = $request->input('ids');
        $ids = explode(',', $ids);

        if(!$ids) {
            return json_encode(array('error' => 'No Ids selected.'));
        }

        $rows = SentMail::select('id', 'type', 'orderid')->whereIn('id', [$ids])->orderBy('id', 'DESC')->get();

        if(!$rows) {
            return json_encode(array('error' => 'No Records Found.'));
        }

        $labels = [];
        // Load items
        foreach($rows as $item) {
            $hasLabel = $this->getLabels($item->orderid, $item->type);
            if(!$hasLabel || !$hasLabel->label_img) {
                continue;
            }
            $labels[] = $hasLabel->label_img;
        }
        if(!$labels) {
            return json_encode(array('error' => 'No Labels Found.'));
        }

        $data['images'] = $labels;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin::post-completion-pipelines.mail-pipeline.partials._dowload_label',$data)->setPaper('letter', 'landscape')->setWarnings(false);
        return $pdf->stream(sprintf('labels_%s.pdf', date('Y_m_d_H_i')));
    }

    /**
    * download Label
    * @param $request
    * @return pdf file
    */
    public function downloadLabel($request)
    {
        $inputs = $request->all();
        $row = $this->shippingItem->where('order_id', $inputs['orderId'])->orderBy('created_date', 'DESC')->first();
        $data['images'] = [$row->label_img];
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('admin::post-completion-pipelines.mail-pipeline.partials._dowload_label',$data)->setPaper('letter', 'landscape')->setWarnings(false);
        return $pdf->download(sprintf('usps_label_%s_%s.pdf', $row->order_id, $row->service));
    }

    /**
     * get Service Rate
     * @return rate
     */
    public function getServiceRate($service)
    {
        foreach($this->getRates() as $rate) {
            if(strtolower($service) == strtolower($rate->service)) {
                return $rate;
            }
        }
        return false;
    }

    /**
     * insert
     * @return void
     */
    public function save($order, $orderType)
    {
        $this->order = $order;
        $data = $this->getLabel()->__toArray(true);
        // Insert new shipping item
        try {
            $shippingItem = $this->shippingItem->create([
                'order_id' => $this->getOrder()->id,
                'shipping_id' => $data['id'],
                'order_type' => $orderType,
                'created_date' => time(),
                'created_by' => getUserId(),
                'service' => $this->getRateService(),
                'fee' => $this->getFee(),
                'label_pdf' => $this->getLabelDocument(),
                'label_img' => $this->getLabelImage(),
                'tracking_number' => $this->getTrackingNumber(),
            ]);
            $id = $shippingItem->id;
            $addresses = ['from_address', 'to_address'];
            // Insert addresses
            foreach($addresses as $address) {
                ShippingAddress::create([
                    'shippment_id' => $id,
                    'address_type' => str_replace('_address', '', $address),
                    'shipping_address_id' => $data[$address]['id'],
                    'name' => $data[$address]['name'],
                    'company' => $data[$address]['company'],
                    'street1' => $data[$address]['street1'],
                    'street2' => $data[$address]['street2'],
                    'city' => $data[$address]['city'],
                    'state' => $data[$address]['state'],
                    'zip' => $data[$address]['zip'],
                    'country' => $data[$address]['country'],
                    'phone' => $data[$address]['phone'],
                    'email' => $data[$address]['email'],
                ]);
            }

        } catch(\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
    * mark Ready To Mail
    * @param $id
    * @return mixed
    */
    public function markReadyToMail($id)
    {
        $row = $this->getById($id);
        if(!$row) {
			return json_encode(['error' => 'Sorry, that record was not found.']);
		}
		$row->update(['is_ready' => 1]);
		return json_encode(['html' => 'ok']);
    }

    /**
    * do Mark Failed
    * @param $id
    * @return mixed
    */
    public function doMarkFailed($id)
    {
        $row = $this->getById($id);
        if(!$row) {
			return json_encode(['error' => 'Sorry, that record was not found.']);
		}
		$row->update(['is_failed' => 1]);
		return json_encode(['html' => 'ok']);
    }

    /**
    * do Mark Delivered
    * @param $id
    * @return mixed
    */
    public function doMarkDelivered($id)
    {
        $row = $this->getById($id);
        if(!$row) {
			return json_encode(['error' => 'Sorry, that record was not found.']);
		}
		$row->update(['delivered_date' => Carbon::now()->timestamp]);
		return json_encode(['html' => 'ok']);
    }

    /**
    * filter data
    * @param $inputs, $docuVaultOrderRepository, $orderRepository
    * @return array
    */
    public function filterData($inputs, $docuVaultOrderRepository = null, $orderRepository = null)
    {
        $data = [];
        if(!empty($inputs))
        {
            foreach ($inputs as $row)
            {

                $nestedData['id'] = $row->id;
                $nestedData['type'] = $row->type == 'appr' ? 'Appraisal' : 'DocuVault';
                $nestedData['borrower'] = is_null($row->aborrower) ? $row->dborrower : $row->aborrower;
                $nestedData['requested_date'] = $row->created_date ? date('m/d/Y G:i A', $row->created_date) : '-';
                $nestedData['marked_sent_date'] = $row->sent_date ? date('m/d/Y G:i A', $row->sent_date) : '-';
                $nestedData['marked_sent_by'] = count($row->sentBy) && $row->sentBy ? $row->sentBy[0]->firstname . ' ' . $row->sentBy[0]->lastname : '';
                $nestedData['delivered_date'] = $row->delivered_date ? date('m/d/Y G:i A', time($row->delivered_date)) : '-';
                $nestedData['requested_by'] = count($row->createdBy) && $row->createdBy ? $row->createdBy[0]->firstname . ' ' . $row->createdBy[0]->lastname : '';
                $nestedData['options'] = $this->options($row);
                if (!is_null($docuVaultOrderRepository) && !is_null($orderRepository)) {
                    $order = $this->getMailOrderRecordByType($row, $docuVaultOrderRepository, $orderRepository);
                    $label = $this->getLabels($order->id, $row->type);
                    $nestedData['checkbox'] = $label && optional($label)->label_img ? $this->checkbox($row) : '';
                }
                $data[] = $nestedData;
            }
        }
        return $data;
    }

    /**
    * render options view
    * @param $row
    * @return view
    */
    private function options($row)
    {
        $view = view('admin::post-completion-pipelines.mail-pipeline.partials._options', ['row' => $row]);
        return $view->render();
    }

    /**
    * render checkbox view
    * @param $row
    * @return view
    */
    private function checkbox($row)
    {
        $view = view('admin::post-completion-pipelines.mail-pipeline.partials._checkbox', ['row' => $row]);
        return $view->render();
    }

    /**
    * table columns
    * @return array
    */
    public function tableColumns()
    {
        return  [
            0 =>'id',
            1 =>'type',
            2 => 'borrower',
            3 => 'requested_date',
            5 => 'marked_sent_date',
            6 => 'marked_sent_by',
            7 => 'delivered_date',
            8 => 'requested_by',
            9 => 'id',
        ];
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getTracker()
    {
        return $this->tracker;
    }
    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    protected function setTracker()
    {
        $this->tracker = $this->label->tracker;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getTrackingNumber()
    {
        return $this->getTracker()->tracking_code;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    public function getParcel()
    {
        return \EasyPost\Parcel::create([
            "predefined_package" => Setting::getSetting('appraisal_shipping_parcel_key', 'FlatRateEnvelope'),
            "weight" => Setting::getSetting('appraisal_shipping_parcel_weight', '3.0')
        ]);
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function isSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function isError()
    {
        return count($this->errors);
    }

    /**
     * Gets the value of order.
     *
     * @return mixed
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Sets the value of order.
     *
     * @param mixed $order the order
     *
     * @return self
     */
    protected function setOrder($order)
    {
        $this->order = $order;
        return $this;
    }

    /**
     * Sets the value of errors.
     *
     * @param mixed $errors the errors
     *
     * @return self
     */
    protected function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Gets the value of hasError.
     *
     * @return mixed
     */
    public function getHasError()
    {
        return $this->hasError;
    }

    /**
     * Sets the value of hasError.
     *
     * @param mixed $hasError the has error
     *
     * @return self
     */
    protected function setHasError($hasError)
    {
        $this->hasError = $hasError;
        return $this;
    }

    /**
     * Gets the value of isSuccess.
     *
     * @return mixed
     */
    public function getIsSuccess()
    {
        return $this->isSuccess;
    }

    /**
     * Sets the value of isSuccess.
     *
     * @param mixed $isSuccess the is success
     *
     * @return self
     */
    protected function setIsSuccess($isSuccess)
    {
        $this->isSuccess = $isSuccess;
        return $this;
    }

    /**
     * Sets the value of shipment.
     *
     * @param mixed $shipment the shipment
     *
     * @return self
     */
    protected function setShipment($shipment)
    {
        $this->shipment = $shipment;
        return $this;
    }

    /**
     * Sets the value of label.
     *
     * @param mixed $label the label
     *
     * @return self
     */
    protected function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Gets the value of rate.
     *
     * @return mixed
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Sets the value of rate.
     *
     * @param mixed $rate the rate
     *
     * @return self
     */
    protected function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    /**
     * Gets the value of fee.
     *
     * @return mixed
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Sets the value of fee.
     *
     * @param mixed $fee the fee
     *
     * @return self
     */
    protected function setFee($fee)
    {
        $this->fee = $fee;
        return $this;
    }

    /**
     * Sets the value of parcel.
     *
     * @param mixed $parcel the parcel
     *
     * @return self
     */
    protected function setParcel($parcel)
    {
        $this->parcel = $parcel;
        return $this;
    }

    /**
     * Gets the value of postageLabel.
     *
     * @return mixed
     */
    public function getPostageLabel()
    {
        return $this->postageLabel;
    }

    /**
     * Sets the value of postageLabel.
     *
     * @param mixed $postageLabel the postage label
     *
     * @return self
     */
    protected function setPostageLabel($postageLabel)
    {
        $this->postageLabel = $postageLabel;
        return $this;
    }

    /**
     * Gets the value of labelDocument.
     *
     * @return mixed
     */
    public function getLabelDocument()
    {
        return $this->labelDocument;
    }

    /**
     * Sets the value of labelDocument.
     *
     * @param mixed $labelDocument the label document
     *
     * @return self
     */
    protected function setLabelDocument($labelDocument)
    {
        $this->labelDocument = $labelDocument;
        return $this;
    }

    /**
     * Gets the value of labelDocument.
     *
     * @return mixed
     */
    public function getLabelImage()
    {
        return $this->labelImage;
    }

    /**
     * Sets the value of labelDocument.
     *
     * @param mixed $labelDocument the label document
     *
     * @return self
     */
    protected function setLabelImage($labelImage)
    {
        $this->labelImage = $labelImage;
        return $this;
    }

    /**
     * Gets the value of rateAmount.
     *
     * @return mixed
     */
    public function getRateAmount()
    {
        return $this->rateAmount;
    }

    /**
     * Sets the value of rateAmount.
     *
     * @param mixed $rateAmount the rate amount
     *
     * @return self
     */
    protected function setRateAmount($rateAmount)
    {
        $this->rateAmount = $rateAmount;
        return $this;
    }

    /**
     * Gets the value of rateService.
     *
     * @return mixed
     */
    public function getRateService()
    {
        return $this->rateService;
    }

    /**
     * Sets the value of rateService.
     *
     * @param mixed $rateService the rate service
     *
     * @return self
     */
    protected function setRateService($rateService)
    {
        $this->rateService = $rateService;
        return $this;
    }

    /**
     * Gets the value of rateDeliveryDays.
     *
     * @return mixed
     */
    public function getRateDeliveryDays()
    {
        return $this->rateDeliveryDays;
    }

    /**
     * Sets the value of rateDeliveryDays.
     *
     * @param mixed $rateDeliveryDays the rate delivery days
     *
     * @return self
     */
    protected function setRateDeliveryDays($rateDeliveryDays)
    {
        $this->rateDeliveryDays = $rateDeliveryDays;
        return $this;
    }

    /**
     * Gets the value of fees.
     *
     * @return mixed
     */
    public function getFees()
    {
        return $this->fees;
    }

    /**
     * Sets the value of fees.
     *
     * @param mixed $fees the fees
     *
     * @return self
     */
    protected function setFees($fees)
    {
        $this->fees = $fees;
        return $this;
    }

    /**
     * Sets the value of fromAddress.
     *
     * @param mixed $fromAddress the from address
     *
     * @return self
     */
    protected function setFromAddress($fromAddress)
    {
        $this->fromAddress = $fromAddress;
        return $this;
    }

    /**
     * Sets the value of toAddress.
     *
     * @param mixed $toAddress the to address
     *
     * @return self
     */
    protected function setToAddress($toAddress)
    {
        $this->toAddress = $toAddress;
        return $this;
    }

    /**
     * Gets the value of fromAddress.
     *
     * @return mixed
     */
    public function getFromAddress()
    {
        return $this->fromAddress;
    }

    /**
     * Gets the value of toAddress.
     *
     * @return mixed
     */
    public function getToAddress()
    {
        return $this->toAddress;
    }

    /**
     * Gets the value of rates.
     *
     * @return mixed
     */
    public function getRates()
    {
        return $this->rates;
    }

    /**
     * Sets the value of rates.
     *
     * @param mixed $rates the rates
     *
     * @return self
     */
    protected function setRates($rates)
    {
        $this->rates = $rates;
        return $this;
    }
}
