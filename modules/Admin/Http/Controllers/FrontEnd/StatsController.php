<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\StatsRequest;
use Modules\Admin\Repositories\FrontEnd\StatsRepository;
use App\Models\FrontEnd\Stat;

class StatsController extends AdminBaseController
{
    /**
     * @param StatsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(StatsRepository $repository)
    {
        return $repository->index();
    }

    /**
     * @param StatsRepository $repository
     * @return mixed
     */
    public function data(StatsRepository $repository)
    {
        return $repository->data();
    }

    /**
     * @param StatsRequest $request
     * @param Stat $stat
     * @param StatsRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(StatsRequest $request, Stat $stat, StatsRepository $repository)
    {
        return $repository->create($request, $stat);
    }

    public function edit(Stat $stat, StatsRepository $repository)
    {
        return $repository->edt($stat);
    }

    /**
     * @param StatsRequest $request
     * @param Stat $stat
     * @param StatsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StatsRequest $request, Stat $stat, StatsRepository $repository)
    {
        return $repository->update($request, $stat);
    }

    /**
     * @param Stat $stat
     * @param StatsRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Stat $stat, StatsRepository $repository)
    {
        return $repository->destroy($stat);
    }
}
