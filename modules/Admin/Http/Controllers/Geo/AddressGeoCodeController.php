<?php

namespace Modules\Admin\Http\Controllers\Geo;

use App\Models\Geo\AddressGeoCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Yajra\DataTables\Facades\DataTables;

class AddressGeoCodeController extends AdminBaseController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::geo.address.index');
    }

    /**
     * Get Geo Coded Data for Datatables
     * @param Request $request
     * @return mixed
     */
    public function geoCodeData(Request $request)
    {
        if ($request->ajax()) {
            $addressGeoCode = AddressGeoCode::all();
            return Datatables::of($addressGeoCode)
                ->addColumn('action', function ($r) {
                    return view('admin::geo.address.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * Create Geo Code
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function createGeoCode(Request $request)
    {
        if ($request->isMethod('POST')) {
            if (!empty(trim($request->address))) {
                $geoCode = geoCode($request->address, true);
                
                if (!$geoCode['success']) {
                  return redirect()->back()->withErrors($geoCode['message']);
                }

                Session::flash('success', 'Address Geo Coded.');
                return redirect()->route('admin.geo.address');
            } else {
                return redirect()->back()->withErrors('Address field is required');
            }
        }
        return view('admin::geo.address.geo_code');
    }

    /**
     * @param AddressGeoCode $addressGeoCode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refreshGeoCode(AddressGeoCode $addressGeoCode)
    {
      $geoCode = geoCode($addressGeoCode->fulladdress, true);
                
      if (!$geoCode['success']) {
        return redirect()->back()->withErrors($geoCode['message']);
      }

      Session::flash('success', 'Address Re Geo Coded.');
      return redirect()->route('admin.geo.address');
    }

    /**
     * @param AddressGeoCode $addressGeoCode
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteGeoCode(AddressGeoCode $addressGeoCode)
    {
        $addressGeoCode->delete();
        Session::flash('success', 'Geo Code deleted.');
        return redirect()->route('admin.geo.address');
    }

    /**
     * @param $address
     * @return array|string
     */
    public function geoCode($address)
    {
        
    }
}
