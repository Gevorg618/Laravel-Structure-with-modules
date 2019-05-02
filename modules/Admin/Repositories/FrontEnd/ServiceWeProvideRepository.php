<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Services\CreateS3Storage;
use App\Models\FrontEnd\Service;
use Carbon\Carbon;
use Yajra\Datatables\Datatables;

class ServiceWeProvideRepository
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
        $this->carouselS3Path = 'frontend/service-provide';
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.service_we_provide.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $services = Service::query();
        return Datatables::of($services)
            ->editColumn('icon', function ($r) {
                return view('admin::front-end.service_we_provide.partials._icon', ['row' => $r]);
            })
            ->addColumn('actions', function ($r) {
                return view('admin::front-end.service_we_provide.partials._options', ['row' => $r]);
            })
            ->toJson();
    }

    /**
     * @param $serviceProvide
     * @param $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create($serviceProvide, $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $logo_image = $request->file('logo');
            if ($logo_image) {
                $logo_image_name = $this->generateFileName($logo_image);
                try {
                    $uploaded_logo = $this->uploadFile($logo_image, $logo_image_name);
                    $data['logo'] = $uploaded_logo['absolute_path'];
                    $serviceProvide->create($data);
                } catch (\Exception $e) {
                    $message = $e->getMessage();
                    return [
                        'status' => 0,
                        'message' => $message
                    ];
                }

            }
            return redirect()->route('admin.frontend-site.services.index');
        }

        return view('admin::front-end.service_we_provide.create', compact('serviceProvide'));
    }

    public function edit($serviceProvide)
    {
        return view('admin::front-end.service_we_provide.create', compact('serviceProvide'));
    }

    /**
     * @param $serviceProvide
     * @param $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update($serviceProvide, $request)
    {
        $data = $request->all();
        $logo = $request->file('logo');
        if ($logo) {
            $logo_name = $this->generateFileName($logo);
            try {
                $uploaded_logo = $this->uploadFile($logo, $logo_name);
                $data['logo'] = $uploaded_logo['absolute_path'];
            } catch (\Exception $e) {
                $message = $e->getMessage();
                return [
                    'status' => 0,
                    'message' => $message
                ];
            }
        }
        $serviceProvide->update($data);
        return redirect()->route('admin.frontend-site.services.index');
    }

    /**
     * @param $serviceProvide
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($serviceProvide)
    {
        $s3 = $this->createS3Service->make($this->bucketName);
        $logo_exists = $s3->exists($serviceProvide->logo);;
        if ($logo_exists) {
            $s3->delete($serviceProvide->logo);
        }
        $serviceProvide->delete();
        return redirect()->back();
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
}
