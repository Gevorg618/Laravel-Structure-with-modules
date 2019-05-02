<?php

namespace Modules\Admin\Http\Controllers\Customizations;

use Illuminate\Http\Request;
use Modules\Admin\Repositories\Users\UserRepository;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Repositories\Customizations\TurnTimeByStateRepository;
use Modules\Admin\Http\Requests\Customizations\TurnTimeByStateRequest;

class TurnTimeByStateController extends AdminBaseController {

    /**
    * Index
    * @return view
    */
    public function index(
            TurnTimeByStateRepository $turnTimeByStateRepository,
            UserRepository $userRepository
        )
    {
        $stateTitles = $turnTimeByStateRepository->getTurnTimeByState();
        $stateTitlesFull = $turnTimeByStateRepository->getTurnTimeByState(true);
        $statesList = $turnTimeByStateRepository->getTurnTimeByStatesList();
        $manage = $userRepository->isAdminUser();

        return view('admin::customizations.turn-time-by-state.index',
            compact(
                'stateTitles',
                'stateTitlesFull',
                'statesList',
                'manage'
            )
        );
    }

    /**
    * Update or Create
    * @return view
    */
    public function save(
            TurnTimeByStateRequest $request,
            TurnTimeByStateRepository $turnTimeByStateRepository
        )
    {
        $inputs = $request->all();
        $result = $turnTimeByStateRepository->save($inputs);
        return redirect(route('admin.management.turn-time-by-state'));
    }
}
