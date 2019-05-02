<?php

namespace Modules\Admin\Http\Controllers\Mail;

use Mail;
use App\Mail\Test\TestMail;
use App\Models\Appraisal\Order;
use Modules\Admin\Http\Controllers\AdminBaseController;

class TestController extends AdminBaseController
{
    public function index()
    {
        return $this->email();
    }

    public function send()
    {
        Mail::to('test@test.com')->send($this->email());
        echo 'Sent';
    }

    protected function email()
    {
        $order = Order::query()->isAssigned()->orderBy('id', 'DESC')->first();
        $content = $order->convertKeys(setting('email_template_order_cancel_trip_fee'));

        return new TestMail(sprintf(setting('company_name') . "  - %s - %s", $order->id, $order->propaddress1), $content, $order, request()->get('invite'));
    }
}
