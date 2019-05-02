<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\FrontEnd\NavigationMenu;
use App\Http\Controllers\Controller;
use App\Models\Tools\CustomPage;
use Illuminate\Http\Request;

class CustomPageController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $tempRoute = $request->path();
        $navigationMenu = NavigationMenu::active()->get();
        $page = CustomPage::where('route', $tempRoute)->first();

        abort_unless($page, 404, 'Page was not found.');

        return view('frontend.custom-pages.page', compact('page', 'navigationMenu'));
    }

}
