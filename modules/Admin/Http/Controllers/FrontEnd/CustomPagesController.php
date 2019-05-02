<?php

namespace Modules\Admin\Http\Controllers\FrontEnd;

use Modules\Admin\Http\Controllers\AdminBaseController;

class CustomPagesController extends AdminBaseController
{
    public function index()
    {
        return \Redirect::to('/custom-pages');
    }
}
