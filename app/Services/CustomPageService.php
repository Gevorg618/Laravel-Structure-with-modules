<?php

namespace App\Services;

class CustomPageService
{
    /**
     * @param $customPages
     * @return array
     */
    public function sortCustomPage($customPages)
    {
        if($customPages) {
            $pages = [];
            foreach ($customPages as $customPage) {
                $explodedRoute = explode('/', $customPage->route);
                if(count($explodedRoute) > 1) {
                    $customPage->is_drop_down = true;
                    $needExplodeTitle = explode(' ', $customPage->title);
                    if(strtolower($needExplodeTitle[0]) == strtolower($explodedRoute[0])) {
                        $customPage->title = $needExplodeTitle[1];
                    }
                } else {
                    $customPage->is_drop_down = false;
                }
                $pages[$explodedRoute[0]][] = $customPage;
            }
            return $pages;
        }
        return [];
    }
}