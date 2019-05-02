<?php

namespace Modules\Admin\Repositories\FrontEnd;

use App\Models\FrontEnd\Testimonial;
use Yajra\Datatables\Datatables;

class ClientTestimonialRepository
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::front-end.client_testimonial.index');
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function data()
    {
        $testimonials = Testimonial::query();
        return Datatables::of($testimonials)
            ->editColumn('name', function ($r) {
                $r = $this->explodeNameTitle($r);
                return $r->name;
            })
            ->editColumn('title', function ($r) {
                $r = $this->explodeNameTitle($r);
                return $r->title;
            })
            ->addColumn('action', function ($r) {
                return view('admin::front-end.client_testimonial.partials._options', ['row' => $r]);
            })
            ->tojson();
    }

    /**
     * @param $request
     * @param $testimonial
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create($request, $testimonial)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $testimonial->create($data);
            return redirect()->route('admin.frontend-site.client-testimonials.index');
        }

        $testimonial = $this->explodeNameTitle($testimonial);
        return view('admin::front-end.client_testimonial.create', compact('testimonial'));
    }

    /**
     * @param $testimonial
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($testimonial)
    {
        $testimonial = $this->explodeNameTitle($testimonial);
        return view('admin::front-end.client_testimonial.create', compact('testimonial'));
    }

    /**
     * @param $request
     * @param $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($request, $testimonial)
    {
        $data = $request->all();
        $testimonial->update($data);
        return redirect()->route('admin.frontend-site.client-testimonials.index');
    }

    /**
     * @param $testimonial
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($testimonial)
    {
        $testimonial->delete();
        return redirect()->back();
    }

    /**
     * @param $testimonial
     * @return mixed
     */
    private function explodeNameTitle($testimonial)
    {
        $exploded = explode(',', $testimonial->name);
        for ($i = 0; $i < count($exploded); $i++) {
            if ($i >= 1) {
                if ($i >= 2) {
                    $testimonial->title .= ', ' . $exploded[$i];
                    continue;
                }
                $testimonial->title .= $exploded[$i];
            }
        }
        $testimonial->name = $exploded[0];
        return $testimonial;
    }
}
