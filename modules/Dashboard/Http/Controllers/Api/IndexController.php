<?php

namespace Dashboard\Http\Controllers\Api;

use Dashboard\Http\Controllers\DashboardBaseController;

class IndexController extends DashboardBaseController
{
    public function index()
    {
        return response()->json([]);
    }
}
