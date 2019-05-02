<?php

use Carbon\Carbon;
use App\Helpers\Address;
use App\Services\CreateS3Storage;
use App\Models\Tools\LogoManager;

function companyAddress()
{
  return Address::getFullAddress(setting('company_address'), setting('company_address2'), setting('company_city'), setting('company_state'), setting('company_zip'));
}

function companyCopyright()
{
  return sprintf("&copy; %s %s<br>%s | %s", 
                date('Y'), 
                setting('company_copy'),
                companyAddress(),
                setting('company_phone')
              );
}

function companyLogo($type = 'small')
{
    return setting('company_logo_' . $type);
}

function getCode($title)
{
    $pattern = '/[^0-9a-zA-Z\_]/';
    $title = strtoupper(str_slug($title, '_'));
    $title = preg_replace($pattern, '', $title);
    return $title;
}

function getMaxNumber($n, $jumps=100)
{
    $a = 0;
    for (;;) {
        if ($a > $n) {
            break;
        }
        $a += $jumps;
    }
    return $a;
}

function adminLogo()
{
    $image = LogoManager::where('start_date', '<', Carbon::now()->timestamp)->where('end_date', '>', Carbon::now()->timestamp)->first();
    $url = null;
    if (empty($image)) {
        $url = companyLogo();
    } else {
        $createS3Service = new CreateS3Storage;
        $s3 = $createS3Service->make(config('filesystems.disks.s3.bucket'));
        $url = $s3->url('uploads/'.$image['image']);
    }
    return $url;
}

function getList($rows, $placeholder = false, $unset = false, $currentUser = false)
{
    $list = [];
    if ($placeholder && is_string($placeholder)) {
        $list['0'] = '-- ' . $placeholder . ' --';
    } elseif ($placeholder) {
        $list['0'] = '-- None --';
    }

    if ($unset) {
        $list[config('constants.remove_option')] = '-- Unset Current --';
    }

    if ($currentUser) {
        $list['user_' . admin()->id] = '-- ' . admin()->fullname . ' --';
    }

    $collection = collect($list);
    return $collection->union($rows);
}

/**
 * Convert number of seconds into hours, minutes and seconds
 * and return an array containing those values
 *
 * @param integer $seconds Number of seconds to parse
 * @return array
 */
function secondsToTime($seconds)
{
    // extract hours
    $hours = floor($seconds / (60 * 60));

    // extract minutes
    $divisor_for_minutes = $seconds % (60 * 60);
    $minutes = floor($divisor_for_minutes / 60);

    // extract the remaining seconds
    $divisor_for_seconds = $divisor_for_minutes % 60;
    $seconds = ceil($divisor_for_seconds);

    // return the final array
    $obj = array(
        "h" => (int) ($hours < 10) ? '0' . $hours : $hours,
        "m" => (int) ($minutes < 10) ? '0' . $minutes : $minutes,
        "s" => (int) ($seconds < 10) ? '0' . $seconds : $seconds,
    );
    return $obj;
}
