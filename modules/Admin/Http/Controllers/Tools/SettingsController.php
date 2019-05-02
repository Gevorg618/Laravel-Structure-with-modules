<?php 

namespace Modules\Admin\Http\Controllers\Tools;

use Modules\Admin\Http\Controllers\AdminBaseController;
use App\Models\Tools\{Setting, SettingCategory};
use Yajra\DataTables\Datatables;
use Illuminate\Http\Request;
use Carbon\Carbon, Session, Input, Html, DB, Validator, Response;

class SettingsController extends AdminBaseController {
  
  public function index()
  {
    return view('admin::tools.settings.index');
  }

  /**
   * Process datatables ajax request.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function settingsCategoryData(Request $request)
  {
    $rows = SettingCategory::select(['setting_category.*', DB::raw('COUNT(setting.id) as settings')])
                  ->leftJoin('setting', 'setting.category_id', '=', 'setting_category.id')
                  ->groupBy('setting_category.id');

    return Datatables::of($rows)
        ->editColumn('title', function ($r) {
          return Html::linkRoute('admin.tools.settings.category.view', $r->title, ['id' => $r->id]);
        })
        ->editColumn('settings', function ($r) {
          return $r->settings;
        })
        ->addColumn('action', function ($r) {
          return view('admin::tools.settings.partials._category_options', ['row' => $r]);
        })
        ->make(true);
  }

  public function createCategory(Request $request) {
    $row = new SettingCategory;
    // get the POST data
    $new = Input::all();
    $errors = null;

    if($new) {
      $saved = $row->fill(Input::all())->save();
      // attempt validation
      if ($saved) {
        Session::flash('success', 'Category Saved.');
        return redirect()->route('admin.tools.settings.category.view', ['id' => $row->id]);
      }
    }
    // Display form
    return view('admin::tools.settings.category_form', ['row' => $row]);
  }

  public function updateCategory($id, Request $request) {
    $row = SettingCategory::find($id);

    if(!$row) {
      abort(404, 'Setting Category was not found.');
    }

    if($request->isMethod('post')) {
      // get the POST data
      $saved = $row->fill(Input::all())->save();

      // attempt validation
      if ($saved) {
        Session::flash('success', 'Category Saved.');
        return redirect()->route('admin.tools.settings');
      }
    }

    // Display form
    return view('admin::tools.settings.category_form', ['row' => $row]);
  }

  public function viewCategory($id, Request $request) {
    $row = SettingCategory::with(['settings'])->find($id);

    if(!$row) {
      abort(404, 'Setting Category was not found.');
    }

    if($request->isMethod('post') && $request->input('settings')) {
      foreach($request->input('settings') as $i => $value) {
        (new Setting)->saveSetting($i, $value);
      }

      Setting::resetCache();

      // Redirect
      Session::flash('success', 'Settings Saved.');
      return redirect()->route('admin.tools.settings.category.view', ['id' => $id]);
    }

    return view('admin::tools.settings.category_view', ['row' => $row]);
  }
}