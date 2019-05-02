<?php

namespace App\Models\FrontEnd;

use Illuminate\Database\Eloquent\Model;
use App\Services\CreateS3Storage;

/**
 * @property mixed desktop_image
 * @property mixed mobile_image
 */
class HeaderCarousel extends Model
{
    protected $table = 'header_carousel';

    protected $casts = [
        'buttons' => 'array'
    ];

    protected $fillable = [
        'desktop_image',
        'mobile_image',
        'title',
        'description',
        'position',
        'is_active',
        'buttons'
    ];

    public function scopeActive($query)
    {
      $query->where('is_active', 1);
    }

    /**
     * @return mixed
     */
    public function getDesktopImageAttribute()
    {
        $createS3Service = new CreateS3Storage();
        $s3 = $createS3Service->make(env('S3_BUCKET'));
        return $s3->url($this->attributes['desktop_image']);
    }

    /**
     * @return mixed
     */
    public function getMobileImageAttribute()
    {
        $createS3Service = new CreateS3Storage();
        $s3 = $createS3Service->make(env('S3_BUCKET'));
        return $s3->url($this->attributes['mobile_image']);
    }
}
