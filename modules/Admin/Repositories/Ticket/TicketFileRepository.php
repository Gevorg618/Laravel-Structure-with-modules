<?php

namespace Modules\Admin\Repositories\Ticket;

use App\Models\Ticket\File;
use Modules\Admin\Contracts\Ticket\TicketFileContract;
use App\Services\CreateS3Storage;

class TicketFileRepository implements TicketFileContract
{
    protected $storage;
    protected $defaultImage = '/images/imagenotfound.jpeg';

    /**
     * TicketFileRepository constructor.
     * @param CreateS3Storage $storage
     */
    public function __construct(CreateS3Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param $request
     * @return string
     */
    public function getImage($request)
    {
        // Grab image
        if ($request->fileId) {
            $row = File::find($request->fileId);
        } else {
            $row = File::where('tixid', $request->id)->ofFilename($request->image);
        }

        // Load image
        if ($row) {
            $s3 = $this->storage->make();

            return $s3->get($row->filename);
        }

        return file_get_contents($this->defaultImage);
    }
}