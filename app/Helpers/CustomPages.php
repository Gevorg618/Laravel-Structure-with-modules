<?php

use App\Models\Tools\CustomPage;
use App\Services\CustomPageService;

function getSortedCustomPages()
{
  $customPages = CustomPage::active()->isShown()->get();
  return (new CustomPageService)->sortCustomPage($customPages);
}