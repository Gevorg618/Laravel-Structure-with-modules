<?php

namespace Modules\Admin\Http\Controllers\Tools;

use Illuminate\Support\Facades\Auth;
use App\Models\Tools\Template;
use Illuminate\Http\Request;
use App\Http\Requests;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Datatables;
use Illuminate\Support\Facades\Session;
use Validator;
use Twig_Extension;

class TemplatesController extends AdminBaseController
{
    public function index()
    {
        return view('admin::tools.templates.index');
    }
    public function templatesData(Request $request)
    {
        if ($request->ajax()) {
            $status = Template::with('user','test')->get();
            return Datatables::of($status)
                ->addColumn('action', function ($r) {
                    return view('admin::tools.templates.partials._options', ['row' => $r]);
                })
                ->editColumn('created_by', function ($r) {
                    if($r->user)
                    return $r->user->firstname.' '.$r->user->lastname;
                })
                ->editColumn('last_modified_by', function ($r) {
                    if($r->test)
                    return $r->test->firstname.' '.$r->test->lastname;
                })
                ->editColumn('created_date', function ($r) {
                        return date('m/d/Y H:i', $r->created_date);
                })
                ->editColumn('last_modified', function ($r) {
                        return date('m/d/Y H:i', $r->last_modified);
                })
                ->make(true);
        }
    }

    public function createTemplates(Request $request,Template $template)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $source = $data['source'];
            $loader = new \Twig_Loader_String();
            $twig = new \Twig_Environment($loader);
            $errorMessages = null;
            try {
                $twig->parse($twig->tokenize($source));
            } catch (\Exception $e) {
                $errorMessages = sprintf("Templates source contains syntax errors. Errors: <br />%s", $e->getMessage());
            }
            $validator = Validator::make($data, [
                'name' => 'required|unique:templates,name,NULL,id,deleted_at,NULL',
                'source' => 'required'
            ]);
            $validator->after(function($validator)use($errorMessages) {
                if ($errorMessages) {
                    $validator->errors()->add('code', $errorMessages);
                }
            });
            if ($validator->fails()) {
                return redirect()->route('admin.tools.templates.create')
                    ->withErrors($validator)
                    ->withInput();
            }
            $data['created_date'] = $timestamp = strtotime("now");
            $data['last_modified'] = $data['created_date'];
            $data['created_by'] = admin()->id;
            $create = $template->create($data);
            if ($create) {
                Session::flash('success', 'Template Created.');
                return redirect()->route('admin.tools.templates');
            }
        }
        return view('admin::tools.templates.create', compact('template'));
    }
    public function updateTemplates(Request $request, $id)
    {
        if ($request->isMethod('put')) {
            $data = $request->all();
            $source = $data['source'];
            $loader = new \Twig_Loader_String();
            $twig = new \Twig_Environment($loader);
            $ext = new TwigLandmark();
            $twig->addExtension($ext);
            $errorMessages = null;
            try {
                $twig->parse($twig->tokenize($source));
            } catch (\Exception $e) {
                $errorMessages = sprintf("Templates source contains syntax errors. Errors: <br />%s", $e->getMessage());
            }

            $validator = Validator::make($data, [
                'name' => 'required|unique:templates,name,'.$id,
                'source' => 'required'
            ]);
            $validator->after(function($validator)use($errorMessages) {
                if ($errorMessages) {
                    $validator->errors()->add('code', $errorMessages);
                }
            });
            if ($validator->fails()) {
                return redirect()->route('admin.tools.templates.update',[$id])
                    ->withErrors($validator)
                    ->withInput();
            }
            $template = Template::find($request->id);
            $template->last_modified_by = admin()->id;
            $data['last_modified'] = $timestamp = strtotime("now");

            $update = $template->update($data);
            if ($update) {
                Session::flash('success', 'Template Updated.');
                return redirect()->route('admin.tools.templates');
            }
        }
        $template = Template::find($id);
        return view('admin::tools.templates.create', compact('template'));
    }
    public function deleteTemplates($id)
    {
        $template = Template::find($id);
        $template->delete();
        Session::flash('success', 'Template Deleted.');
        return redirect()->route('admin.tools.templates');
    }
}