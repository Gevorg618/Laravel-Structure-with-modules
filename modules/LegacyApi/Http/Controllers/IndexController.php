<?php

namespace LegacyApi\Http\Controllers;

use LegacyApi\Http\Controllers\BaseController;

class IndexController extends BaseController
{
	/**
     * @return Response
     */
    public function index()
    {
        return response()->json(['Legacy API']);
    }
}
