<?php

namespace Dashboard\Http\Controllers;

use Dashboard\Http\Controllers\DashboardBaseController;

class DashboardController extends DashboardBaseController
{
    public function index()
    {
        return view('dashboard::index');
    }
}
