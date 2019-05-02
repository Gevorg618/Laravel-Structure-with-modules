<?php

namespace App\Console\Commands;

use App\Models\Appraisal\Order;
use App\Models\Appraisal\OrderFile;
use App\Services\CreateS3Storage;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Modules\Admin\Services\InvoiceService;

class GenerateInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:generate {id} {admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an invoice for the order';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(CreateS3Storage $createS3Service, InvoiceService $invoiceService)
    {
        $id = $this->argument('id');
        $adminId = $this->argument('admin');
        $order = Order::find($id);
        if (!$order) {
            $this->output->error('There is no order with specified ID');
            return false;
        }

        $pdf = \PDF::loadView('admin::invoice', [
            'orders' => collect([$order]),
        ]);
        $fileName = 'invoice_' . $id . '.pdf';
        $filePath = public_path('uploads/' . $fileName);
        $pdf->save($filePath);
        $row = $order->files()->where('is_invoice', 1)->first();
        if (!$row) {
            // Create one

            $orderFile = new OrderFile();
            $orderFile->order_id = $id;
            $orderFile->created_at = time();
            $orderFile->created_by = $adminId ?? null;
            $orderFile->is_client_visible = 1;
            $orderFile->is_appr_visible = 0;
            $orderFile->docname = $fileName;
            $orderFile->file_location = 'uploads';
            $orderFile->filename = $fileName;
            $orderFile->is_aws = 1;
            $orderFile->is_invoice = 1;
            $orderFile->file_size = filesize($filePath);
            $orderFile->save();

        } else {
            $row->created_at = time();
            $row->filename = $fileName;
            $row->docname = $fileName;
            $row->file_size = filesize($filePath);
            $row->save();
        }

        $path = '';
        $file = new File($filePath);
        $s3 = $createS3Service->make(env('S3_BUCKET'));
        $s3->putFileAs($path, $file, $fileName);
    }
}
