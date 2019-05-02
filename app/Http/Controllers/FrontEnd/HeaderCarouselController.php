<?php

namespace App\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use App\Models\FrontEnd\HeaderCarousel;
use App\Http\Controllers\Controller;

class HeaderCarouselController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $carouselItems = HeaderCarousel::all();
        return view('index', compact('carouselItems'));
    }
}
