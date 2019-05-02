<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\HeaderCarouselRequest;
use Modules\Admin\Repositories\FrontEnd\HeaderCarouselRepository;
use App\Models\FrontEnd\HeaderCarousel;

class HeaderCarouselController extends AdminBaseController
{
    /**
     * @param HeaderCarouselRepository $carouselRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(HeaderCarouselRepository $carouselRepository)
    {
        return $carouselRepository->index();
    }

    /**
     * @param HeaderCarouselRepository $carouselRepository
     * @return mixed
     * @throws \Exception
     */
    public function data(HeaderCarouselRepository $carouselRepository)
    {
        return $carouselRepository->data();
    }

    /**
     * @param HeaderCarouselRequest $request
     * @param HeaderCarousel $carousel
     * @param HeaderCarouselRepository $carouselRepository
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(HeaderCarouselRequest $request, HeaderCarousel $carousel, HeaderCarouselRepository $carouselRepository)
    {
        return $carouselRepository->create($request, $carousel);
    }

    /**
     * @param HeaderCarousel $carousel
     * @param HeaderCarouselRepository $carouselRepository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(HeaderCarousel $carousel, HeaderCarouselRepository $carouselRepository)
    {
        return $carouselRepository->edit($carousel);
    }

    /**
     * @param HeaderCarouselRequest $request
     * @param HeaderCarousel $carousel
     * @param HeaderCarouselRepository $carouselRepository
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update(HeaderCarouselRequest $request, HeaderCarousel $carousel, HeaderCarouselRepository $carouselRepository)
    {
        return $carouselRepository->update($request, $carousel);
    }

    /**
     * @param HeaderCarousel $carousel
     * @param HeaderCarouselRepository $carouselRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(HeaderCarousel $carousel, HeaderCarouselRepository $carouselRepository)
    {
        return $carouselRepository->destroy($carousel);
    }
}
