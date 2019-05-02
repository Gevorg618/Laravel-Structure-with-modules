<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\TeamMemberRequest;
use Modules\Admin\Repositories\FrontEnd\TeamMemberRepository;
use App\Models\FrontEnd\TeamMember;

class TeamMemberController extends AdminBaseController
{
    /**
     * @param TeamMemberRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(TeamMemberRepository $repository)
    {
        return $repository->index();
    }

    /**
     * @param TeamMemberRepository $repository
     * @return mixed
     */
    public function data(TeamMemberRepository $repository)
    {
        return $repository->data();
    }

    /**
     * @param TeamMemberRequest $request
     * @param TeamMember $member
     * @param TeamMemberRepository $repository
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(TeamMemberRequest $request, TeamMember $member, TeamMemberRepository $repository)
    {
        return $repository->create($request, $member);
    }

    public function edit(TeamMember $member, TeamMemberRepository $repository)
    {
        return $repository->edit($member);
    }

    /**
     * @param TeamMemberRequest $request
     * @param TeamMember $member
     * @param TeamMemberRepository $repository
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update(TeamMemberRequest $request, TeamMember $member, TeamMemberRepository $repository)
    {
        return $repository->update($request, $member);
    }

    /**
     * @param TeamMember $member
     * @param TeamMemberRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(TeamMember $member, TeamMemberRepository $repository)
    {
        return $repository->destroy($member);
    }
}
