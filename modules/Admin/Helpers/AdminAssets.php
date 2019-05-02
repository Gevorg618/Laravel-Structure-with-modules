<?php

/**
 * Module Asset
 * @return [type] [description]
 */
function masset($path, $module='admin') {
  return asset('modules/' . $module . '/' . config('admin.theme') . '/' . $path);
}