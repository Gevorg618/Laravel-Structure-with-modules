<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use App\Models\FrontEnd\NavigationMenu;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\NavigationMenuRequest;
use Modules\Admin\Repositories\FrontEnd\NavigationMenuRepository;

class NavigationController extends AdminBaseController
{
    /**
     * @param NavigationMenuRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(NavigationMenuRepository $repository)
    {
        return $repository->index();
    }

    /**
     * @param NavigationMenuRepository $repository
     * @return mixed
     */
    public function data(NavigationMenuRepository $repository)
    {
        return $repository->data();
    }

    /**
     * @param NavigationMenu $navigationMenu
     * @param NavigationMenuRequest $request
     * @param NavigationMenuRepository $repository
     * @return mixed
     */
    public function create(NavigationMenu $navigationMenu, NavigationMenuRequest $request,  NavigationMenuRepository $repository)
    {
        return $repository->create($navigationMenu, $request);
    }

    public function edit(NavigationMenu $navigationMenu, NavigationMenuRepository $repository)
    {
        return $repository->edit($navigationMenu);
    }

    /**
     * @param NavigationMenu $navigationMenu
     * @param NavigationMenuRequest $request
     * @param NavigationMenuRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NavigationMenu $navigationMenu, NavigationMenuRequest $request, NavigationMenuRepository $repository)
    {
        return $repository->update($navigationMenu, $request);
    }

    /**
     * @param NavigationMenu $navigationMenu
     * @param NavigationMenuRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(NavigationMenu $navigationMenu, NavigationMenuRepository $repository)
    {
        return $repository->destroy($navigationMenu);
    }
}
