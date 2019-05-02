<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Models\FrontEnd\NavigationMenu;
use Yajra\Datatables\Datatables;

class NavigationMenuRepository
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.navigation_menu.index');
    }

    /**
     * @return mixed
     */
    public function data()
    {
        $headerCarousel = NavigationMenu::query();
        return Datatables::of($headerCarousel)
            ->addColumn('action', function ($r) {
                return view('admin::front-end.navigation_menu.partials._options', ['row' => $r]);
            })
            ->tojson();
    }


    public function create($navigationMenu, $request)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            $childes = $this->sortChildes($data);
            $data['childes'] = $childes;
            $navigationMenu->create($data);
            return redirect()->route('admin.frontend-site.navigation-menu.index');
        }

        return view('admin::front-end.navigation_menu.create', compact('navigationMenu'));
    }

    /**
     * @param $navigationMenu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($navigationMenu)
    {
        return view('admin::front-end.navigation_menu.create', compact('navigationMenu'));
    }

    /**
     * @param $navigationMenu
     * @param $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($navigationMenu, $request)
    {
        $data = $request->all();
        $childes = $this->sortChildes($data);
        $data['childes'] = $childes;
        $data['is_drop_down'] = isset($data['is_drop_down']) ? $data['is_drop_down'] : false;
        $navigationMenu->update($data);
        return redirect()->route('admin.frontend-site.navigation-menu.index');
    }

    /**
     * @param $navigationMenu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($navigationMenu)
    {
        $navigationMenu->delete();
        return redirect()->back();
    }

    /**
     * @param $data
     * @return array|null
     */
    private function sortChildes($data)
    {
        $childes = isset($data['is_drop_down']) ? [] : null;
        if (isset($data['is_drop_down']) && isset($data['child_url'])) {
            foreach ($data['child_title'] as $i => $value) {
                $childes[$value] = $data['child_url'][$i];
            }
        }

        return $childes;
    }
}
