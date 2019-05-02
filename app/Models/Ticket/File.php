<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class File extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_files';
    protected $fillable = [
        'tixid',
        'created_at',
        'created_by',
        'filename',
        'is_aws',
        'file_size',
    ];

    public $timestamps = false;

    /**
     * @param $query
     * @param bool|string $name
     * @return mixed
     */
    public function scopeOfFilename($query, $name = false)
    {
        if ($name) {
            return $query->orWhere('filename', 'like', '%_' . $name . '%')
                ->orWhere('filename', 'like', $name . '%')
                ->orWhere('filename', 'like', $name);
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfInvoice($query)
    {
        return $query->where('is_invoice', 1);
    }

    /**
     * @param $query
     * @return mixed
     */
    public function scopeOfIcc($query)
    {
        return $query->where('is_icc', 1);
    }

    /**
     * @return string
     */
    public function getFormatFileSizeAttribute()
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $position = 0;
        $value = $this->file_size;

        do {
            if ($value < 1024) {
                return round($value, 2) . ' ' . $units[$position];
            }

            $value = $value / 1024;
            $position++;
        } while ($position < count($units));

        return number_format($value, 2) . ' ' . end($units);
    }
}
