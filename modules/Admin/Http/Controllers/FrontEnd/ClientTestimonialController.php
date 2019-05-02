<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\FrontEnd\ClientTestimonialRequest;
use Modules\Admin\Repositories\FrontEnd\ClientTestimonialRepository;
use App\Models\FrontEnd\Testimonial;

class ClientTestimonialController extends AdminBaseController
{
    /**
     * @param ClientTestimonialRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(ClientTestimonialRepository $repository)
    {
        return $repository->index();
    }

    /**
     * @param ClientTestimonialRepository $repository
     * @return mixed
     */
    public function data(ClientTestimonialRepository $repository)
    {
        return $repository->data();
    }

    /**
     * @param ClientTestimonialRequest $request
     * @param Testimonial $testimonial
     * @param ClientTestimonialRepository $repository
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create(ClientTestimonialRequest $request, Testimonial $testimonial, ClientTestimonialRepository $repository)
    {
        return $repository->create($request, $testimonial);
    }

    /**
     * @param Testimonial $testimonial
     * @param ClientTestimonialRepository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Testimonial $testimonial, ClientTestimonialRepository $repository)
    {
        return $repository->edit($testimonial);
    }

    /**
     * @param ClientTestimonialRequest $request
     * @param Testimonial $testimonial
     * @param ClientTestimonialRepository $repository
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function update(ClientTestimonialRequest $request, Testimonial $testimonial, ClientTestimonialRepository $repository)
    {
        return $repository->update($request, $testimonial);
    }

    /**
     * @param Testimonial $testimonial
     * @param ClientTestimonialRepository $repository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Testimonial $testimonial, ClientTestimonialRepository $repository)
    {
        return $repository->destroy($testimonial);
    }
}
