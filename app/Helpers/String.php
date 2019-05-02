<?php

use Illuminate\Http\UploadedFile;

setlocale(LC_MONETARY, 'en_US.UTF-8');

function currency($number, $format = '%.2n')
{
    return money_format($format, $number);
}

function s3Path($path)
{
    return sprintf('%s/%s/%s', $path, date('Y'), date('m'));
}

function s3Filename(UploadedFile $file)
{
    return strtolower(Carbon::now()->format('Y_m_d') . '_' . str_random(10) . '_' . str_replace(' ', '_', $file->getClientOriginalName()));
}