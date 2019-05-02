<?php

namespace App\Models\FrontEnd;

use App\Services\CreateS3Storage;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'logo',
        'icon',
        'title',
        'description'
    ];

    /**
     * @return mixed
     */
    public function getLogoAttribute()
    {
        $createS3Service = new CreateS3Storage();
        $s3 = $createS3Service->make(env('S3_BUCKET'));
        return $s3->url($this->attributes['logo']);
    }}
