<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Ticket\Ticket;
use App\Models\Users\User;
use App\Models\Documents\{ UserDoc, UserGroupDocuments };
use App\Models\Ticket\File;
use App\Models\Appraisal\{ Order, OrderLog, OrderFile, ApprOrderReportPhotosImage };
use App\Models\DocuVault\OrderFiles as DocuvaultOrderFiles;
use App\Models\DocuVault\OrderLog as DocuvaultOrderLog;
use App\Models\DocuVault\Order as DocuvaultOrder;
use App\Models\AlternativeValuation\Order as AltOrder;
use App\Models\AlternativeValuation\OrderLog as AltOrderLog;

const SYSTEM_STATS_CACHE_TIME = 60*60;

/**
* Get Tickets System Statistic
* @return array
*/
function getSystemStatsTickets() {

    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTickets');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $total = Ticket::count();
    $value = [
        'Tickets' => $total,
    ];
    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get Total Users System Statistic
* @return array
*/
function getSystemStatsTotalUsers() {

    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTotalUsers');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $clients = User::where('user_type', 5)->count();
    $appraisers = User::where('user_type', 4)->count();
    $agents = User::where('user_type', 14)->count();
    $users = User::count();

    $value = [
        'Clients' => $clients,
        'Appraisers' => $appraisers,
        'Agents' => $agents,
        'Total' => $users
    ];

    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get Total Orders System Statistic
* @return array
*/
function getSystemStatsTotalOrders() {
    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTotalOrders');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $appr = Order::count();
    $al = AltOrder::count();
    $docuvault = DocuvaultOrder::count();

    $value = [
        'Appraisals' => $appr,
        'MarkItValue' => $al,
        'DocuVault' => $docuvault,
    ];

    $value['Total'] = array_sum($value);
    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get Total Order Logs System Statistic
* @return array
*/
function getSystemStatsTotalOrderLogs() {
    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTotalOrderLogs');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $appr = OrderLog::count();
    $al = AltOrderLog::count();
    $docuvault = DocuvaultOrderLog::count();

    $value = [
        'Appraisals' => $appr,
        'MarkItValue' => $al,
        'DocuVault' => $docuvault,
    ];

    $value['Total'] = array_sum($value);
    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get Total Order Files System Statistic
* @return array
*/
function getSystemStatsTotalOrderFiles() {
    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTotalOrderFiles');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $appr = OrderFile::count();
    $al = 0;
    $docuvault = DocuvaultOrderFiles::count();

    $value = [
        'Appraisals' => $appr,
        'MarkItValue' => $al,
        'DocuVault' => $docuvault,
    ];

    $value['total'] = array_sum($value);
    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get Total Other Files System Statistic
* @return array
*/
function getSystemStatsTotalOtherFiles() {
    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTotalOtherFiles');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $users = UserDoc::count();
    $groups = UserGroupDocuments::count();
    $tickets = File::count();

    $value = [
        'Users' => $users,
        'Groups' => $groups,
        'Tickets' => $tickets,
    ];

    $value['Total'] = array_sum($value);
    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get Total File Sizes System Statistic
* @return array
*/
function getSystemStatsTotalFileSizes() {
    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsTotalFileSizes');
    $value = Cache::get($cacheKey);

    if($value) {
        return $value;
    }

    $appr = OrderFile::sum('file_size');
    $reportPhotos = ApprOrderReportPhotosImage::sum('file_size');
    $users = UserDoc::sum('filesize');
    $tickets = File::sum('file_size');
    $docu = DocuvaultOrderFiles::sum('file_size');

    $value = [
        'Appraisals' => ($appr + $reportPhotos),
        'Users' => $users,
        'Tickets' => $tickets,
        'DocuVault' => $docu,
    ];

    $value['Total'] = array_sum($value);
    Cache::add($cacheKey, $value, SYSTEM_STATS_CACHE_TIME);
    return $value;
}

/**
* Get DB Info System Statistic
* @return array
*/
function getSystemStatsDBInfo() {
    $cacheKey = sprintf('system_stats_%s', 'getSystemStatsDataInfo');
    $rows = Cache::get($cacheKey);

    if($rows) {
        return $rows;
    }

    $informationSchema = DB::connection('information_schema');
    $tables = $informationSchema->table('TABLES')->where('TABLE_SCHEMA', env('DB_DATABASE', 'forge'))->count();
    $rows = $informationSchema->table('TABLES')->where('TABLE_SCHEMA', env('DB_DATABASE', 'forge'))->sum('TABLE_ROWS');
    $dataLength = $informationSchema->table('TABLES')->where('TABLE_SCHEMA', env('DB_DATABASE', 'forge'))->sum('DATA_LENGTH');
    $indexLength = $informationSchema->table('TABLES')->where('TABLE_SCHEMA', env('DB_DATABASE', 'forge'))->sum('INDEX_LENGTH');
    DB::disconnect('information_schema');

    $return = [
        'Tables' => $tables,
        'Rows' => $rows,
        'Size' => $dataLength + $indexLength,
    ];
    Cache::add($cacheKey, $return, SYSTEM_STATS_CACHE_TIME);
    return $return;
}

/**
* Formating File Size
* @param $value, $decimals
* @return integer
*/
function formatFileSize($value, $decimals = 0)
{
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
    $position = 0;

    do {
        if ($value < 1024) {
            return round($value, $decimals) . ' ' . $units[$position];
        }

        $value = $value / 1024;
        $position++;
    } while ($position < count($units));

    return number_format($value, $decimals) . ' ' . end($units);
}
