<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Models\FrontEnd\LatestNews;
use App\Services\CreateS3Storage;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class LatestNewsRepository
{
    protected $createS3Service;

    private $bucketName;

    private $carouselS3Path;

    /**
     * HeaderCarouselRepository constructor.
     */
    public function __construct()
    {
        $this->createS3Service = new CreateS3Storage;
        $this->bucketName = env('S3_BUCKET');
        $this->carouselS3Path = s3Path('frontend/latest-news');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.latest_news.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $latestNews = LatestNews::query();
        return Datatables::of($latestNews)
            ->addColumn('action', function ($r) {
                return view('admin::front-end.latest_news.partials._options', ['row' => $r]);
            })
            ->tojson();
    }

    /**
     * @param $request
     * @param $latestNews
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create($request, $latestNews)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $image = $request->file('image');
            if ($image) {
                $image_name = $this->generateFileName($image);
                try {
                    $uploaded_image = $this->uploadFile($image, $image_name);
                    $data['image'] = $uploaded_image['absolute_path'];
                    $latestNews->create($data);
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    return [
                        'status' => 0,
                        'message' => $message
                    ];
                }
            }
            return redirect()->route('admin.frontend-site.latest-news.index');
        }

        return view('admin::front-end.latest_news.create', compact('latestNews'));
    }

    /**
     * @param $latestNews
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($latestNews)
    {
        return view('admin::front-end.latest_news.create', compact('latestNews'));
    }

    /**
     * @param $request
     * @param $latestNews
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update($request, $latestNews)
    {
        $data = $request->all();
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
        $latestNews->update($data);
        return redirect()->route('admin.frontend-site.latest-news.index');
    }

    /**
     * @param $latestNews
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($latestNews)
    {
        $s3 = $this->createS3Service->make($this->bucketName);
        $image_exists = $s3->exists($latestNews->image);
        if ($image_exists) {
            $s3->delete($latestNews->image);
        }
        $latestNews->delete();
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
        $s3->putFileAs($this->carouselS3Path, $file, $generatedFileName, $this->createS3Service->getFileVisibility('public'));
        return [
            'success' => 1,
            'absolute_path' => $this->carouselS3Path . '/' . $generatedFileName
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
