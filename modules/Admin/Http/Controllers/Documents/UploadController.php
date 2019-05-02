<?php

namespace Modules\Admin\Http\Controllers\Documents;

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Documents\RemoteFileRequest;
use Yajra\Datatables\Datatables;
use App\Models\Documents\RemoteFile;

class UploadController extends AdminBaseController
{
    protected $createS3Service;

    public function __construct()
    {
        $this->createS3Service = new \App\Services\CreateS3Storage;
    }

    public function index()
    {
        return view('admin::document.upload.index');
    }

    /**
     * Process Datatables with uploaded files after AJAX call
     * @param Request $request
     * @return mixed
     */
    public function uploadedData(Request $request)
    {
        if ($request->ajax()) {
            $files = RemoteFile::with('user')->get();

            return Datatables::of($files)
                ->editColumn('name', function ($r) {
                    $s3 = $this->createS3Service->make($r->bucket);
                    $url = $s3->url($r->filename);

                    return view('admin::document.upload.partials._names', ['row' => $r, 'url' => $url]);
                })
                ->editColumn('created_by', function ($r) {
                    return $r->user->email;
                })
                ->editColumn('is_public', function ($r) {
                    return $r->is_public ? 'Yes' : 'No';
                })
                ->addColumn('action', function ($r) {
                    return view('admin::document.upload.partials._options', ['row' => $r]);
                })
                ->make(true);
        }
    }

    /**
     * Upload file to server after POST request
     * @param RemoteFileRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadFile(RemoteFileRequest $request)
    {
        $file = $request->file('file');

        $name = $file->getClientOriginalName();
        $path = 'uploads/' . date('Y') . '/' . date('m');

        // Upload file to server
        $s3 = $this->createS3Service->make($request->bucket);

        // putFile automatically controls the streaming of this file to the storage
        $s3->putFileAs($path, $file, $name, $this->createS3Service->getFileVisibility($request->is_public));

        // Add a new entry to the database
        RemoteFile::create([
            'name' => $name,
            'path' => $path,
            'bucket' => $request->bucket,
            'created_by' => admin()->id,
            'is_public' => $request->is_public,
        ]);

        return back()->with('success', 'File Uploaded successfully');
    }

    /**
     * @param RemoteFile $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(RemoteFile $file)
    {
        $newStatus = !$file->is_public;

        // Update RemoteFile in the database
        $file->update(['is_public' => $newStatus, 'updated_by' => admin()->id]);

        // Update RemoteFile in the S3 storage
        $s3 = $this->createS3Service->make($file->bucket);
        $s3->setVisibility($file->filename, $this->createS3Service->getFileVisibility($newStatus));

        return back()->with('success', 'Visible status is updated');
    }

    /**
     * @param RemoteFile $file
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteFile(RemoteFile $file)
    {
        $s3 = $this->createS3Service->make($file->bucket);
        $exists = $s3->exists($file->filename);

        // Delete RemoteFile from database
        $file->delete();

        if ($exists) {
            // Delete RemoteFile from S3 storage
            $s3->delete($file->filename);

            return back()->with('success', 'File was successfully deleted');
        }
    }
}