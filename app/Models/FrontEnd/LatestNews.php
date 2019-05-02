<?php

namespace App\Models\FrontEnd;

use App\Services\CreateS3Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class LatestNews extends Model
{
    use Sluggable;

    protected $table = 'latest_news';

    protected $fillable = [
        'title',
        'image',
        'short_description',
        'content',
        'is_active'
    ];

    public function scopeActive($query)
    {
      $query->where('is_active', 1);
    }

    /**
     * @return mixed
     */
    public function getImageAttribute()
    {
        $createS3Service = new CreateS3Storage();
        $s3 = $createS3Service->make(env('S3_BUCKET'));
        return $s3->url($this->attributes['image']);
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
