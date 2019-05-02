<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Models\FrontEnd\HeaderCarousel;
use App\Services\CreateS3Storage;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class HeaderCarouselRepository
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
        $this->carouselS3Path = 'frontend/carousel';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.header_carousel.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $headerCarousel = HeaderCarousel::query();
        return Datatables::of($headerCarousel)
            ->addColumn('action', function ($r) {
                return view('admin::front-end.header_carousel.partials._options', ['row' => $r]);
            })
            ->tojson();
    }

    /**
     * @param $request
     * @param $carousel
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create($request, $carousel)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            $buttons = [];
            if ($data['buttons_title'] && $data['buttons_link']) {
                foreach ($data['buttons_title'] as $i => $value) {
                    $buttons[$value] = $data['buttons_link'][$i];
                }
                $data['buttons'] = $buttons;
            }

            $desktop_image = $request->file('desktop_image');
            $mobile_image = $request->file('mobile_image');

            if ($desktop_image && $mobile_image) {
                $desktop_image_name = $this->generateFileName($desktop_image);
                $mobile_image_name = $this->generateFileName($mobile_image);
                try {
                    $uploaded_desktop_image = $this->uploadFile($desktop_image, $desktop_image_name);
                    $uploaded_mobile_image = $this->uploadFile($mobile_image, $mobile_image_name);
                    $data['desktop_image'] = $uploaded_desktop_image['absolute_path'];
                    $data['mobile_image'] = $uploaded_mobile_image['absolute_path'];
                    $carousel->create($data);
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    return [
                        'status' => 0,
                        'message' => $message
                    ];
                }
            }
            return redirect()->route('admin.frontend-site.header-carousel.index');
        }

        return view('admin::front-end.header_carousel.create', compact('carousel'));
    }

    /**
     * @param $carousel
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($carousel)
    {
        return view('admin::front-end.header_carousel.create', compact('carousel'));
    }

    /**
     * @param HeaderCarousel $carousel
     * @param $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update($request, $carousel)
    {
        $data = $request->all();
        $buttons = [];
        if ($data['buttons_title'] && $data['buttons_link']) {
            foreach ($data['buttons_title'] as $i => $value) {
                $buttons[$value] = $data['buttons_link'][$i];
            }
            $data['buttons'] = $buttons;
        }

        $desktop_image = $request->file('desktop_image');
        $mobile_image = $request->file('mobile_image');

        if ($desktop_image) {
            $desktop_image_name = $this->generateFileName($desktop_image);
            try {
                $uploaded_desktop_image = $this->uploadFile($desktop_image, $desktop_image_name);
                $data['desktop_image'] = $uploaded_desktop_image['absolute_path'];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                return [
                    'status' => 0,
                    'message' => $message
                ];
            }
        }
        if ($mobile_image) {
            $mobile_image_name = $this->generateFileName($mobile_image);
            try {
                $uploaded_mobile_image = $this->uploadFile($mobile_image, $mobile_image_name);
                $data['mobile_image'] = $uploaded_mobile_image['absolute_path'];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                return [
                    'status' => 0,
                    'message' => $message
                ];
            }
        }
        $carousel->update($data);
        return redirect()->route('admin.frontend-site.header-carousel.index');
    }

    /**
     * @param HeaderCarousel $carousel
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($carousel)
    {
        $s3 = $this->createS3Service->make($this->bucketName);

        $desktop_exists = $s3->exists($carousel->desktop_image);
        $mobile_exists = $s3->exists($carousel->mobile_image);
        if ($desktop_exists) {
            $s3->delete($carousel->desktop_image);
        }
        if ($mobile_exists) {
            $s3->delete($carousel->mobile_image);
        }

        $carousel->delete();
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
        $path = sprintf("%s/%s/%s", $this->carouselS3Path, date('Y'), date('m'));
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
