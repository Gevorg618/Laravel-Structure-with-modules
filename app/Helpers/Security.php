<?php

function realProxy()
{
    return $_SERVER['REMOTE_ADDR'] ?? '';
}

function realIp()
{
    try {
        $ip = \Request::ip();
    } catch(\Exception $e) {
        $ip = realProxy();
    }

    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
        $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $first = reset($ips);
        if($first) {
            $ip = $first;
        }
    }

    if(isset($_SERVER['HTTP_CF_CONNECTING_IP']) && $_SERVER['HTTP_CF_CONNECTING_IP']) {
        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }

    return $ip;
}