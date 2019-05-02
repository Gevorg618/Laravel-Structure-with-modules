<?php

namespace Modules\Admin\Services\Ticket;

use App\Models\AlternativeValuation\OrderLog as AltOrderLog;
use App\Models\AlternativeValuation\OrderLogType as AltOrderLogType;
use App\Models\Documents\UserDoc;
use App\Models\Users\Certification;
use App\Models\Ticket\File;
use App\Models\OrderDocuments\UserGroup;
use App\Models\OrderDocuments\Lender;
use App\Models\Clients\Client;
use App\Models\Tools\UserLog;
use App\Models\Management\WholesaleLenders\UserGroupLender;

class OrderService
{
    protected $document;

    /**
     * OrderService constructor.
     *
     * @param \Modules\Admin\Contracts\OrderDocumentContract $document
     */
    public function __construct(\Modules\Admin\Contracts\Ticket\OrderDocumentContract $document)
    {
        $this->document = $document;
    }

    /**
     * @param \App\Models\Appraisal\Order $order
     * @return array
     */
    public function getAllOrderFiles($order)
    {
        $files = [];

        if (!$order) {
            return $files;
        }

        $files = $this->processOrderFiles($files, $order);
        $files = $this->processInvoiceDocument($files, $order);
        $files = $this->processICCDocument($files, $order);
        $files = $this->processUserDocument($files, $order, 'ins', 'E & O Document');
        $files = $this->processUserDocument($files, $order, 'w9', 'W9 Document');
        $files = $this->processCertificates($files, $order);
        $files = $this->processDocuments($files, $order);

        return $files;
    }

    /**
     * @param array $data
     */
    public function createOrderLogEntry($data)
    {
        $search = [
            "'<script[^>]*>.*?</script>'si",  // remove js
            "'<style[^>]*>.*?</style>'si", // remove css
            "'<head[^>]*>.*?</head>'si", // remove head
            "'<link[^>]*>.*?</link>'si", // remove link
            "'<object[^>]*>.*?</object>'si",
            "/<html[^>]+>/i"
        ];

        $replace = [
            "",
            "",
            "",
            "",
            "",
            "<html>"
        ];

        $data['body'] = preg_replace($search, $replace, $data['body']);
        $data['body'] = str_replace("'", "\'", $data['body']);

        if (isset($data['type'])) {
            if ($data['type'] == config('app.order_type_appraisal')) {
                $params = [
                    'public' => 'N',
                    'ticketId' => $data['ticketId'],
                    'email' => $data['from'] ?: '',
                    'html_content' => $data['body'],
                ];

                $this->addOrderLog($data['orderId'], $data['subject'], $params);

            } elseif ($data['type'] == config('app.order_type_alt')) {
                $typeCode = $data['orderType'] ?? 'EMAIL';

                $type = AltOrderLogType::where('code', $typeCode)->first();

                AltOrderLog::create([
                    'order_id' => intval($data['orderId']),
                    'type_id' => $type->id,
                    'ticketId' => $data['ticketId'],
                    'message' => $data['subject'],
                    'html_content' => $data['body'],
                    'email' => $data['from'] ?? '',
                    'dts' => date('Y-m-d H:i'),
                ]);
            }
        }
    }

    /**
     * @param int $id
     * @param string $message
     * @param array $params
     */
    public function addOrderLog($id, $message, $params = [])
    {
        $data = [
            'orderId' => $id,
            'info' => $message,
        ];

        if ($params) {
            $data = array_merge($data, $params);
        }

        UserLog::create($data);
    }

    /**
     * @param array $files
     * @param \App\Models\Appraisal\Order $order
     * @return array
     */
    protected function processOrderFiles($files, $order)
    {
        $orderFiles = $order->files()->orderBy('created_at', 'desc')->get();

        foreach ($orderFiles as $file) {
            if ($file->is_aws) {
                $files['fileid-' . $file->id] = $this->getDocInfo($file->docname, $file->created_at, $file->file_size);

            } else {
                $date = false;
                $size = 0;

                if ($file->filePath && is_file($file->filePath)) {
                    $date = filemtime($file->filePath);
                    $size = filesize($file->filePath);
                }

                $files[$file->filePath] = $this->getDocInfo($file->docname, $date, $size);
            }
        }

        return $files;
    }

    /**
     * @param array $files
     * @param \App\Models\Appraisal\Order $order
     * @return array
     */
    protected function processInvoiceDocument($files, $order)
    {
        $doc = $this->getInvoiceDocument($order->id, true);
        if ($doc) {
            $files['fileid-' . $doc->id] = $this->getDocInfo($doc->filename, $doc->created_at, $doc->file_size);
        }

        return $files;
    }

    /**
     * @param array $files
     * @param \App\Models\Appraisal\Order $order
     * @return array
     */
    protected function processICCDocument($files, $order)
    {
        $doc = $this->getICCDocument($order->id, true);
        if ($doc) {
            $files['fileid-' . $doc->id] = $this->getDocInfo($doc->filename, $doc->created_at, $doc->file_size);
        }

        return $files;
    }

    /**
     * @param array $files
     * @param \App\Models\Appraisal\Order $order
     * @param string $type
     * @param string $name
     * @return array
     */
    protected function processUserDocument($files, $order, $type, $name)
    {
        $doc = UserDoc::where('userid', $order->acceptedby)->ofType($type)->orderBy('created_date', 'desc')->first();
        if ($doc) {
            $files['userid-' . $doc->id] = $this->getDocInfo($name, $doc->created_date, $doc->filesize);
        }

        return $files;
    }

    /**
     * @param array $files
     * @param \App\Models\Appraisal\Order $order
     * @return array
     */
    protected function processCertificates($files, $order)
    {
        $states = getStates();

        // Get State Certifications
        $certs = Certification::where('user_id', $order->acceptedby)->get();
        foreach ($certs as $cert) {
            $doc = UserDoc::where('userid', $order->acceptedby)
                ->ofType('state_license')
                ->ofName(sprintf('%s_%s_cert_doc', $order->acceptedby, $cert->state))
                ->orderBy('created_date', 'desc')
                ->first();

            if ($doc) {
                $name = $states[$cert->state] . ' Certification Document';
                $files['userid-' . $doc->id] = $this->getDocInfo($name, $doc->created_date, $doc->filesize);
            }
        }

        return $files;
    }

    /**
     * @param array $files
     * @param \App\Models\Appraisal\Order $order
     * @return array
     */
    protected function processDocuments($files, $order)
    {
        $documents = $this->getOrderDocumentsByLocationCode('ADDITIONAL_DOCUMENTS', $order);

        foreach ($documents as $doc) {
            $name = $this->getOrderDocumentFileName($doc->fullname, $order);
            $files['global-' . $doc->id] = $this->getDocInfo($name, $doc->created_date, $doc->file_size);
        }

        return $files;
    }

    /**
     * @param int $orderId
     * @param bool $refresh
     * @return \Illuminate\Database\Eloquent\Collection|bool
     */
    protected function getInvoiceDocument($orderId, $refresh = false)
    {
        // Check if we have that enabled
        if (!config('app.invoice_enable')) {
            return false;
        }

        // See if we have one
        $row = File::where('order_id', $orderId)->ofInvoice()->first();

        if (!$row || $refresh) {
            // Create
            // TODO: LM-2 print invoice
            shell_exec("php /printinvoice.php $orderId");
            $row = File::where('order_id', $orderId)->ofInvoice()->first();
        }

        // Return document
        return $row;
    }

    /**
     * @param int $orderId
     * @param bool $refresh
     * @return \Illuminate\Database\Eloquent\Collection|bool
     */
    protected function getICCDocument($orderId, $refresh = false)
    {
        // Check if we have that enabled
        if (!config('app.icc_enable')) {
            return false;
        }

        // See if we have one
        $row = File::where('order_id', $orderId)->ofIcc()->orderBy('id', 'desc')->first();

        if (!$row || $refresh) {
            // Create
            // TODO: LM-2 icc cert
            shell_exec("php /icc_cert.php $orderId");
            $row = File::where('order_id', $orderId)->ofIcc()->orderBy('id', 'desc')->first();
        }

        // Return document
        return $row;
    }

    /**
     * @param string $code
     * @param \App\Models\Appraisal\Order|bool $order
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getOrderDocumentsByLocationCode($code, $order = false)
    {
        if ($order) {
            $rows = $this->document->getOrderDocumentsByLocationCode($order, $code);
        } else {
            $rows = $this->document->getDocumentsByLocationCode($code);
        }

        $group = Client::find($order->groupid);
        if ($group) {
            $this->filterOrderDocuments($order, $group, $rows);
        }

        return $rows;
    }

    /**
     * @param \App\Models\Appraisal\Order $order
     * @param \App\Models\Clients\Client $group
     * @param \Illuminate\Database\Eloquent\Collection $documents
     */
    protected function filterOrderDocuments($order, $group, $documents)
    {
        foreach ($documents as $k => $row) {
            // If the row has group ids set then check that one of them matches
            // if not match remove, if not groups set then leave as is as it's for all groups

            $keep = false;
            $filters = false;

            // Get document groups
            $groups = UserGroup::where('file_id', $row->id);

            if ($groups->count()) {
                $groups = $groups->where('user_group_id', $group->id);
                if ($groups->count()) {
                    $keep = true;
                }

                $filters = true;
            }

            // Lenders
            if ($order->lender_id > 0) {
                $lenders = UserGroupLender::where('file_id', $row->id);
                if ($lenders->count()) {
                    $lenders = $lenders->where('lender_id', $order->lender_id);
                    if ($lenders->count()) {
                        $keep = true;
                    }

                    $filters = true;
                }
            }

            if (!$keep && $filters) {
                unset($documents[$k]);
            }
        }
    }

    /**
     * @param string $name
     * @param string $date
     * @param string $size
     * @return array
     */
    protected function getDocInfo($name, $date, $size)
    {
        return [
            'name' => $name,
            'date' => $date ? date('m/d/Y g:i A', $date) : config('constants.not_available'),
            'size' => $size
        ];
    }

    /**
     * @param string $name
     * @param \App\Models\Appraisal\Order $order
     * @return string
     */
    protected function getOrderDocumentFilename($name, $order)
    {
        if (!$order) {
            return $name;
        }

        $keys = ['{id}', '{address}', ',', ' '];
        $values = [$order->id, $order->propaddress1, ' ', '_'];
        return str_replace($keys, $values, $name);
    }
}