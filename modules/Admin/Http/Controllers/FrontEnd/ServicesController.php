<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\ServiceRequest;
use Modules\Admin\Repositories\FrontEnd\ServiceWeProvideRepository;
use App\Models\FrontEnd\Service;

class ServicesController extends AdminBaseController
{
    /**
     * @param ServiceWeProvideRepository $carouselRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ServiceWeProvideRepository $carouselRepository)
    {
        return $carouselRepository->index();
    }

    /**
     * @param ServiceWeProvideRepository $carouselRepository
     * @return mixed
     * @throws \Exception
     */
    public function data(ServiceWeProvideRepository $carouselRepository)
    {
        return $carouselRepository->data();
    }

    /**
     * @param Service $serviceProvide
     * @param ServiceRequest $request
     * @param ServiceWeProvideRepository $carouselRepository
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(Service $serviceProvide, ServiceRequest $request, ServiceWeProvideRepository $carouselRepository)
    {
        return $carouselRepository->create($serviceProvide, $request);
    }

    /**
     * @param Service $serviceProvide
     * @param ServiceWeProvideRepository $repository
     * @return mixed
     */
    public function edit(Service $serviceProvide, ServiceWeProvideRepository $repository)
    {
        return $repository->edit($serviceProvide);
    }

    /**
     * @param Service $serviceProvide
     * @param ServiceRequest $request
     * @param ServiceWeProvideRepository $carouselRepository
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update(Service $serviceProvide, ServiceRequest $request, ServiceWeProvideRepository $carouselRepository)
    {
        return $carouselRepository->update($serviceProvide, $request);
    }

    /**
     * @param Service $serviceProvide
     * @param ServiceWeProvideRepository $carouselRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Service $serviceProvide, ServiceWeProvideRepository $carouselRepository)
    {
        return $carouselRepository->destroy($serviceProvide);
    }
}
