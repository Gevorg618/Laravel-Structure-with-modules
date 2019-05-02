<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Models\FrontEnd\TeamMember;
use App\Services\CreateS3Storage;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class TeamMemberRepository
{
    protected $createS3Service;

    private $bucketName;

    private $teamMemberS3Path;

    /**
     * HeaderCarouselRepository constructor.
     */
    public function __construct()
    {
        $this->createS3Service = new CreateS3Storage;
        $this->bucketName = env('S3_BUCKET');
        $this->teamMemberS3Path = 'frontend/team-members';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.team_member.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $teamMembers = TeamMember::query();
        return Datatables::of($teamMembers)
            ->addColumn('action', function ($r) {
                return view('admin::front-end.team_member.partials._options', ['row' => $r]);
            })
            ->tojson();
    }

    /**
     * @param $request
     * @param $member
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create($request, $member)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $social_links = [];
            if ($data['social_icon'] && $data['social_url']) {
                foreach ($data['social_icon'] as $i => $value) {
                    $social_links[$value] = $data['social_url'][$i];
                }
                $data['social_links'] = $social_links;
            }

            $image = $request->file('image');

            if ($image) {
                $image_name = $this->generateFileName($image);
                try {
                    $uploaded_image = $this->uploadFile($image, $image_name);
                    $data['image'] = $uploaded_image['absolute_path'];
                    $member->create($data);
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    return [
                        'status' => 0,
                        'message' => $message
                    ];
                }
            }
            return redirect()->route('admin.frontend-site.team-member.index');
        }

        return view('admin::front-end.team_member.create', compact('member'));
    }

    /**
     * @param $member
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($member)
    {
        return view('admin::front-end.team_member.create', compact('member'));
    }

    /**
     * @param $request
     * @param $member
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update($request, $member)
    {
        $data = $request->all();
        $social_links = [];
        if ($data['social_icon'] && $data['social_url']) {
            foreach ($data['social_icon'] as $i => $value) {
                $social_links[$value] = $data['social_url'][$i];
            }
            $data['social_links'] = $social_links;
        }

        $image = $request->file('image');

        if ($image) {
            $image_name = $this->generateFileName($image);
            try {
                $uploaded_image = $this->uploadFile($image, $image_name);
                $data['image'] = $uploaded_image['absolute_path'];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                return [
                    'status' => 0,
                    'message' => $message
                ];
            }
        }
        $member->update($data);
        return redirect()->route('admin.frontend-site.team-member.index');
    }

    /**
     * @param $member
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($member)
    {
        $s3 = $this->createS3Service->make($this->bucketName);

        $image_exists = $s3->exists($member->image);
        if ($image_exists) {
            $s3->delete($member->image);
        }
        $member->delete();
        return redirect()->back();
    }

    /**
     * @param $file
     * @param $generatedFileName
     * @return array
     */
    private function uploadFile($file, $generatedFileName)
    {
        $s3 = $this->createS3Service->make($this->bucketName);
        $path = sprintf("%s/%s/%s", $this->teamMemberS3Path, date('Y'), date('m'));
        $s3->putFileAs($path, $file, $generatedFileName, $this->createS3Service->getFileVisibility('public'));
        return [
            'success' => 1,
            'absolute_path' => $path . '/' . $generatedFileName
        ];
    }

    /**
     * @param $file
     * @return string
     */
    private function generateFileName($file)
    {
        $timestamp = Carbon::now()->format('Y-m-d');
        $fileOriginalName = $file->getClientOriginalName();
        $generatedFileName = $timestamp . '_' . str_random(10) . '-' . str_replace(' ', '-', $fileOriginalName);
        return strtolower($generatedFileName);
    }
}
