<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\FrontEnd\{LatestNews, navigationMenu};
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    /**
     * @param $slug
     * @param LatestNews $row
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function view($slug, LatestNews $row)
    {
        $navigationMenu = NavigationMenu::active()->get();
        $latestNews = LatestNews::active()->where('id', '!=', $row->id)->orderBy('created_at', 'desc')->get();
        return view('frontend.news.post', compact('row', 'navigationMenu', 'latestNews'));
    }
}
