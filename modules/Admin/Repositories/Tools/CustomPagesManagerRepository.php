<?php

namespace Modules\Admin\Repositories\Tools;

use App\Models\Tools\CustomPage;
use Yajra\DataTables\Datatables;
use App\Services\CreateS3Storage;
use DB;

class CustomPagesManagerRepository
{   
    /**
     * Object of CustomPage class.
     *
     * @var $customPage
     */
    private $customPage;

    /*
     * bucket name
     * 
     */
    private $bucketName;

    /*
     * uploaded file visibility 
     * 
     */
    private $fileVisibility = true;

    /**
     * Create a new instance of CustomPagesManagerRepository class.
     *
     * @return void
     */
    public function __construct()
    {
        $this->customPage = new CustomPage();
        $this->bucketName = env('S3_BUCKET');
    }

    /**
     * find custom page by id
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function findById($id)
    {
      return $this->customPage->find($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function showCustomPage($id)
    {
        $customPage = $this->customPage->find($id);

        if (!$customPage->is_active && !isAdmin()) {
            return redirect()->route('admin.login');
        }
        return redirect()->to('/' . $customPage->route);
    }

    /**
     * insert data to DB (new custom page) 
     *
     *
     * @param object $request 
     * 
     * @return mixed
     */
    public function create($request)
    {
        
        $requestData = $request->all();

        if ($request->file('logo_image'))  {
           
            // Save uploaded file
            $file = $request->file('logo_image');
            
            $generatedFileName = s3Filename($file);

            $requestData['logo_image'] = $generatedFileName;

        } else {
            
            $file = null;

            unset($requestData['logo_image']);
        
        }

        try {
            DB::beginTransaction();

            $createdCustomPage = $this->customPage->create($requestData);

            if ($file) {
                $this->uploadFile($file, $generatedFileName, $createdCustomPage->id);
            }

            DB::commit();

            $response = [
                'success' => true,
                'message' => 'Custom Page Successfully Created',
                'data' => $createdCustomPage
            ];

            return $response;

        } catch (Exception $exception) {
            DB::rollBack();
            $message = $exception->getMessage();
            $response = [
                'success' => false,
                'message' => $message
            ];
            return $response;
        }
    }

    /**
     * remove custom page
     *
     * @param integer $id
     *
     * @return mixed
     */
    public function delete($id)
    {
      $customPage = $this->findById($id);
      
      if ($customPage) {  
        
        $deleteS3Dir = $this->deleteS3Dir($id);

        return $customPage->delete();
       
      } else {
        return false;
      }
       
    }

    /**
     * update custom page
     *
     * @param integer $id
     * @param array $data
     *
     * @return mixed
     */
    public function update($id, $request)
    {
        $customPage = $this->findById($id);

        if ($customPage) {

            $requestData = $request->all();

            if ($request->file('logo_image'))  {           
                // Save uploaded file
                $file = $request->file('logo_image');
                $generatedFileName = s3Filename($file);
                $requestData['logo_image'] = $generatedFileName;

            } else if ($requestData['file_remove']){
                
                $this->deleteFile($customPage->logo_image, $customPage->id);

                $requestData['logo_image'] = '';
                unset($requestData['file_remove']);

                $file = null;
            } else {

                $file = null;
                unset($requestData['logo_image']);            
            }

            try {
                DB::beginTransaction();

                $updateCustomPage = $customPage->update($requestData);

                if ($file) {
                    $this->uploadFile($file, $generatedFileName, $customPage->id);
                }

                DB::commit();
                $response = [
                    'success' => true,
                    'message' => 'Custom Page Successfully Updated',
                ];
                return $response;

            } catch (Exception $exception) {
                DB::rollBack();

                $message = $exception->getMessage();
                $response = [
                    'success' => false,
                    'message' => $message
                ];
                return $response;
            }

        } else {

            $response = [
                    'success' => false,
                    'message' => "Custom Page is not found."
                ];
            return $response;
        }
        
    }

    /**
     * upload file
     *
     * @param $file
     *
     * @return response
     */
    public function uploadFile($file, $generatedFileName, $customPageId)
    {
        $createS3Service = new CreateS3Storage();
        
        $customPath = 'custom-pages/'.$customPageId;

        $path = s3Path($customPath);

        // Upload file to server
        $s3 = $createS3Service->make($this->bucketName);
        
        // putFile automatically controls the streaming of this file to the storage
        $s3->putFileAs($path, $file, $generatedFileName, $createS3Service->getFileVisibility($this->fileVisibility));

        return true;   
    }


    /**
     * remove custom page file from s3 
     * 
     * @param string $generatedFileName
     * @param integer $customPageId
     *
     * @return boolean
     */
    public function deleteFile($generatedFileName, $customPageId)
    {   
        $createS3Service = new CreateS3Storage();
        
        $s3 = $createS3Service->make($this->bucketName);

        $path = 'custom-pages/'.$customPageId.'/'.$generatedFileName;
        
        $exists = $s3->exists($path);

        if ($exists) {

            // remove Custom Page file from S3 storage
            $s3->delete($path);
            return true;
        
        } else {

            return false;
        }
    }

    /**
     * delete custom page directory from s3 
     * 
     * @param integer $customPageId
     *
     * @return boolean
     */
    public function deleteS3Dir($customPageId)
    {   
        $createS3Service = new CreateS3Storage();
        
        $s3 = $createS3Service->make($this->bucketName);

        $path = 'custom-pages/'.$customPageId;
        
        $exists = $s3->exists($path);

        if ($exists) {

            // remove Custom Page file from S3 storage
            $s3->deleteDirectory($path);
            return true;
        
        } else {

            return false;
        }
    }


    /**
     * get custom pages for dataTable
     *
     * @return array $customPagesDataTables
     */
    public function customPagesDataTables()
    {
        $customPages = $this->customPage->with('createdBy')->get();

        $customPagesDataTables = Datatables::of($customPages)
                ->editColumn('options', function ($customPage) {
                    return view('admin::tools.custom-pages-manager.partials._options', ['customPage' => $customPage])->render();
                })
                ->editColumn('active', function ($customPage) {
                    return $customPage->is_active ? 'Yes' : 'No';
                })
                ->editColumn('created_by', function ($customPage) {
                    return $customPage->createdBy ? $customPage->createdBy->userData->firstname.' '.$customPage->createdBy->userData->lastname: '';
                })
                ->editColumn('modified_by', function ($customPage) {
                    return $customPage->modified_by ? $customPage->createdBy->userData->firstname.' '.$customPage->createdBy->userData->lastname : '';
                })
                ->editColumn('created_date', function ($customPage) {
                        return date('m/d/Y H:i', $customPage->created_date);
                })
                ->editColumn('modified_date', function ($customPage) {
                        return date('m/d/Y H:i', $customPage->modified_date);
                })
                ->rawColumns(['options'])
                ->make(true);
                
        return $customPagesDataTables;
    }

    
}