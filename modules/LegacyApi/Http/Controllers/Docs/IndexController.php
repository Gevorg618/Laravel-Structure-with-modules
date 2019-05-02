<?php

namespace LegacyApi\Http\Controllers\Docs;

use LegacyApi\Http\Controllers\BaseController;

class IndexController extends BaseController
{
	/**
     * @return Response
     */
    public function index()
    {
        return response()->json('Invalid Request.');
    }
}
