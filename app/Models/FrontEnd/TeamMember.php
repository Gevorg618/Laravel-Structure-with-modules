<?php

namespace App\Models\FrontEnd;

use App\Services\CreateS3Storage;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    protected $table = 'team_members';

    protected $casts = [
        'social_links' => 'array'
    ];

    protected $fillable = [
        'image',
        'name',
        'title',
        'social_links',
    ];

    /**
     * @return mixed
     */
    public function getImageAttribute()
    {
        $createS3Service = new CreateS3Storage();
        $s3 = $createS3Service->make(env('S3_BUCKET'));
        return $s3->url($this->attributes['image']);
    }
}
