<?php

namespace Modules\Admin\Http\Controllers\Tools;

use App\Models\Tools\EmailSent;
use App\Models\Tools\EmailSentBody;
use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Datatables;

class EmailsSentController extends AdminBaseController
{
    public function index()
    {
        return view('admin::tools.emails-sent.index');
	}

    /**
     * @param Request $request
     * @return mixed
     */
    public function emailsSentData(Request $request)
    {
        if ($request->ajax()) {
            $emailsSent = EmailSent::select(['id','subject','from_email','to_email','cc_email','date_human','is_read']);
            return Datatables::of($emailsSent)
                ->editColumn('subject',function($r){
                    $subject = ($r->subject)?:'No Subject';
                    $link = '<a href="#" data-id="'.$r->id.'" class="get-email-body">'.$subject.'</a>';
                    return $link;
                })
                ->editColumn('is_read',function($r){
                    return ($r->is_read)?'Yes':'No';
                })
                ->make(true);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function getEmailBody(Request $request)
    {
        if(!empty($request->id)){
            $emailsSent = EmailSent::select(['id'])->where('id',$request->id)->first();
            return view('admin::tools.emails-sent.partials._modal',compact('emailsSent'));
        }
    }

    public function loadIframe($id)
    {
        $emailsSent = EmailSentBody::select('body')->where('email_id', $id)->first();
        if($emailsSent){
            return $emailsSent->body;
        }
        return 'Empty Data';
    }

}
