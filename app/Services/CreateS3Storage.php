<?php

namespace App\Services;
use Illuminate\Support\Facades\Storage;

class CreateS3Storage
{
    const PUBLIC = 'public';
    const PRIVATE = 'private';

    function make(  $bucket = false)
    {
        $config = config('filesystems.disks.s3');
        if (!$bucket) {
            $bucket = $config['bucket'];
        }

        return Storage::createS3Driver([
            'driver' => 's3',
            'key' => $config['key'],
            'secret' => $config['secret'],
            'region' => $config['region'],
            'bucket' => $bucket
        ]);
    }

    function getFileVisibility($status)
    {
        return $status ? self::PUBLIC : self::PRIVATE;
    }


    /**
     * @param $file
     * @return string
     */
    public function downloadFile($file)
    {
        $s3 = Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+30 minutes";

        $command = $client->getCommand('GetObject', array(
            'Bucket'                     => env('S3_BUCKET'),
            'Key'                        => $file,
            'ResponseContentDisposition' => 'attachment; filename="' . $file . '"',
        ));

        $signedUrl = $client->createPresignedRequest($command, $expiry);
        return (string) $signedUrl->getUri();
    }





}
