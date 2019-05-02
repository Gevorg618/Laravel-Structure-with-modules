<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\LatestNewsRequest;
use Modules\Admin\Repositories\FrontEnd\LatestNewsRepository;
use App\Models\FrontEnd\LatestNews;

class LatestNewsController extends AdminBaseController
{
    /**
     * @param LatestNewsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(LatestNewsRepository $repository)
    {
        return $repository->index();
    }

    /**
     * @param LatestNewsRepository $repository
     * @return mixed
     */
    public function data(LatestNewsRepository $repository)
    {
        return $repository->data();
    }

    /**
     * @param LatestNewsRequest $request
     * @param LatestNews $latestNews
     * @param LatestNewsRepository $repository
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(LatestNewsRequest $request, LatestNews $latestNews, LatestNewsRepository $repository)
    {
        return $repository->create($request, $latestNews);
    }

    /**
     * @param LatestNews $latestNews
     * @param LatestNewsRepository $repository
     * @return mixed
     */
    public function edit(LatestNews $latestNews, LatestNewsRepository $repository)
    {
        return $repository->edit($latestNews);
    }

    /**
     * @param LatestNewsRequest $request
     * @param LatestNews $latestNews
     * @param LatestNewsRepository $repository
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update(LatestNewsRequest $request, LatestNews $latestNews, LatestNewsRepository $repository)
    {
        return $repository->update($request, $latestNews);
    }

    /**
     * @param LatestNews $latestNews
     * @param LatestNewsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(LatestNews $latestNews, LatestNewsRepository $repository)
    {
        return $repository->destroy($latestNews);
    }
}
