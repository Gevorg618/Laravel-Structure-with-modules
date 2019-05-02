<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Models\Tools\CustomPage;
use App\Http\Controllers\Controller;
use App\Models\FrontEnd\{
    HeaderCarousel, Service, TeamMember, Testimonial, LatestNews, NavigationMenu, Stat
};

class IndexController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $carouselItems = HeaderCarousel::active()->get();
        $navigationMenu = NavigationMenu::active()->get();
        $latestNews = LatestNews::active()->orderBy('created_at', 'desc')->get();
        $teamMembers = TeamMember::all();
        $stats = Stat::all();
        $servicesWeProvide = Service::all();
        $clientTestimonials = Testimonial::all();

        $contactUs = collect(['lat' => setting('latitude'), 'long' => setting('longitude')]);

        return view('frontend.index',
            compact(
                'carouselItems',
                'servicesWeProvide',
                'teamMembers',
                'clientTestimonials',
                'latestNews',
                'navigationMenu',
                'latestNews',
                'stats',
                'contactUs'
            )
        );
    }
}
