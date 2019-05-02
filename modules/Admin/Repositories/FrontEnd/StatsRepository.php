<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Models\FrontEnd\Stat;
use Yajra\Datatables\Datatables;

class StatsRepository
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.stats.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $stats = Stat::query();
        return Datatables::of($stats)
            ->addColumn('action', function ($r) {
                return view('admin::front-end.stats.partials._options', ['row' => $r]);
            })
            ->tojson();
    }

    /**
     * @param $request
     * @param $stat
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create($request, $stat)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $stat->create($data);
            return redirect()->route('admin.frontend-site.stats.index');
        }

        return view('admin::front-end.stats.create', compact('stat'));
    }

    /**
     * @param $stat
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($stat)
    {
        return view('admin::front-end.stats.create', compact('stat'));
    }

    /**
     * @param $request
     * @param $stat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($request, $stat)
    {
        $data = $request->all();
        $stat->update($data);
        return redirect()->route('admin.frontend-site.stats.index');
    }

    /**
     * @param $stat
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($stat)
    {
        $stat->delete();
        return redirect()->back();
    }
}
