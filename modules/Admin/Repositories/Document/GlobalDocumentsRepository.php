<?php

namespace Modules\Admin\Repositories\Document;

use App\Models\OrderDocuments\Document;
use App\Services\CreateS3Storage;
use Yajra\DataTables\Datatables;
use App\Models\Appraisal\Order;

class GlobalDocumentsRepository
{

    /**
     * global document uplooded file path in s3 bucket
     *
     * @var $documentS3Path
     */
    private $documentS3Path;

    /**
     * Object of CreateS3Storage class
     *
     * @var $createS3Service
     */
    private $createS3Service;

    /*
     * S3 bucket name
     * 
     */
    private $bucketName;

    /*
     * uploaded file visibility 
     * 
     */
    private $fileVisibility = true;

    /**
     * DocumentRepository constructor.
     */
    public function __construct()
    {
        $this->createS3Service = new CreateS3Storage();
        $this->bucketName = env('S3_BUCKET');
        $this->documentS3Path = 'global-documents';
    }

    /**
     * get document by id
     */
    public function getDocumentById($id)
    {
       return Document::where('id', $id)->first();
    }

	/**
     * create documents
     *
     * @param  array $globalDocumentData
     * @param  array $attachData
     * @param  file $file
     * @return collection
     */
    public function create($globalDocumentData, $attachData, $file)
    {
        $fileData = $this->fileData($file);
		$uploadedFile = $this->uploadFile($file, $fileData['file_location']);
		
		if ($uploadedFile['success']) {

			try {

				$globalDocumentData['file_location'] = $fileData['file_location'];
				$globalDocumentData['file_size'] =  $fileData['file_size'];
				
				$createdDoc = Document::create($globalDocumentData);

				$attachData = $this->otherTableAction($createdDoc, $attachData);

                if (!$attachData['success']) {
                    $response = [
                       'success' => false,
                       'message' => $attachData['message']
                    ];
                }

	    	} catch (\Exception $e) {

			    $message = $e->getMessage();
			    
			    $response = [
			       'success' => false,
			       'message' => $message
			    ];
	    	}

		} else {

			$response = [
		       'success' => false,
		       'message' => $uploadedFile['message']
		    ];
		}

        return  [
            'success' => true,
            'message' => 'The Global Documnet Successfully Created',
        ];
    }


    /**
     * update documents
     *
     * @param  int  $id
     * @param  array $globalDocumentData
     * @param  array $attachData
     * @param  file $file
     * 
     * @return array
     */
    public function update($id, $globalDocumentData, $attachData, $file)
    {
        
        // get document by id   
        $document = $this->getDocumentById($id);
        
        // if isset document     
        if ($document) {
            
            // if file uploaded for update
            if ($file) {

                $fileData = $this->fileData($file);
                $uploadedFile = $this->uploadFile($file, $fileData['file_location']);

                // if file was succesfully uploaded in s3 bucket
                if ($uploadedFile['success']) {
                    
                    // add to array parametrs for update
                    $globalDocumentData['file_location'] = $fileData['file_location'];
                    $globalDocumentData['file_size'] =  $fileData['file_size'];

                } else {

                    // return message (error)  
                    return  [ 'success' => false, 'message' => $uploadedFile['message'] ];
                }
            }
            
            try {
            
                // update document params
                $document->update($globalDocumentData);   
            
            } catch (\Exception $e) {

                $message = $e->getMessage();
                
                return ['success' => false, 'message' => $message];
            }         

            // other data array
            $detachData = [
                'lenders' => [],
                'clients' => [],
                'states' => [],
                'types' => [],
                'loan_types' => [],
                'loan_reasons' => [],
                'property_types' => [],
                'occupancy_statuses' => [],
                'locations' => []
            ];

            $dataOther = array_merge($detachData, $attachData);

            $udpateOtherData = $this->otherTableAction($document, $dataOther);

            if (!$udpateOtherData['success']) {
                return  ['success' => false, 'message' => $udpateOtherData['message'] ];
            }

        } else {
            return  [
                'success' => false,
                'message' => 'The Global Documnet not found',
            ];
        }

        return  [
            'success' => true,
            'message' => 'The Global Documnet Successfully Updaeted',
        ];
    }

    /**
     * generate file name
     *
     * @param  $file
     * @return collection
     */
    public function fileData($file)
    {	
    	$timestamp = strtotime("now");

        $fileOriginalName = $file->getClientOriginalName();
        $fileExtension = \File::extension($fileOriginalName);
        $generatedFileName = $timestamp.'_'.str_random(5);
        $fileLocation = $generatedFileName.'.'.$fileExtension;
        $fileSize = $file->getClientSize();

        $fileData = [
        	'file_location' => $fileLocation,
        	'file_size' => $fileSize
        ];

        return $fileData;
    }


    /**
     * upload file
     *
     * @param  file $file
     * @param  string $generatedFileName
     * @return response
     */
    public function uploadFile($file, $generatedFileName)
    {

        try {

    		// Upload file to server
	        $s3 = $this->createS3Service->make($this->bucketName);
	        
	        // putFile automatically controls the streaming of this file to the storage
	        $uploadedFile = $s3->putFileAs($this->documentS3Path, $file, $generatedFileName, $this->createS3Service->getFileVisibility($this->fileVisibility));

	        $response = [
		       'success' => true
		   	];

    	} catch (\Exception $e) {

		    $message = $e->getMessage();

		    $response = [
		       'success' => false,
		       'message' => $message
		    ];
    	}

    	return $response;
    }

    /**
     * get custom pages for dataTable
     *
     * @return array $documentsDataTables
     */
    public function documentsDataTables()
    {
        $documents = Document::orderBy('file_name')->get();

        $documentsDataTables = Datatables::of($documents)
                ->editColumn('options', function ($document) {
                    return view('admin::document.global.partials._options', ['document' => $document])->render();
                })
                ->editColumn('created', function ($document) {
                    return date('m/d/Y H:i', $document->created_date);
                })
                ->editColumn('user', function ($document) {
                    return $document->createdBy ? $document->createdBy->userData->firstname.' '.$document->createdBy->userData->lastname: '';
                })               
                ->editColumn('active', function ($document) {
                   return $document->is_active ? 'Yes' : 'No';
                })
                ->editColumn('client', function ($document) {
                    return $document->is_client_visible ? 'Visible' : 'Hidden';
                })
                ->editColumn('appraiser', function ($document) {
                    return $document->is_appr_visible ? 'Visible' : 'Hidden';
                })
                ->rawColumns(['options'])
                ->make(true);
                
        return $documentsDataTables;
    }

    /**
     * delete document
     *
     * @param  $id
     * @return collection
     */
    public function delete($id)
    {   
        $document = $this->getDocumentById($id);

        if ($document) {

            $detachData = [
                'lenders' => [],
                'clients' => [],
                'states' => [],
                'types' => [],
                'loan_types' => [],
                'loan_reasons' => [],
                'property_types' => [],
                'occupancy_statuses' => [],
                'locations' => []
            ];

            $detachDataResponse = $this->otherTableAction($document, $detachData);
            
            if ($detachDataResponse['success']) {
                
                $document->delete();

                $response = [
                   'success' => true,
                   'message' => 'Document was successfuly deleted'
                ];

            } else {
                $response = [
                   'success' => false,
                   'message' => $detachDataResponse['message']
                ];
            }

        } else {

            $response = [
               'success' => false,
               'message' => 'Document is not found.'
            ];
        }

        return $response;

    }

    /**
     * upload file
     *
     * @param object $document
     * @param  array $attachData
     * @return response
     */
    public function otherTableAction($document, $data)
    {
    	foreach ($data as $type => $dataType) {

            try {

        		switch ($type) {
        			case 'lenders':
        				$document->lenderPivot()->sync($dataType);
                        break;
        			case 'clients':
        			    $document->clientPivot()->sync($dataType);
                        break;
        			case 'states':
                        $document->statePivot()->sync($dataType);
                        break;			
        			case 'types':
                        $document->apprTypePivot()->sync($dataType);
        			    break;
        			case 'loan_types':
        			    $document->loanTypePivot()->sync($dataType);
                        break;
        			case 'loan_reasons':
                        $document->loanReasonPivot()->sync($dataType);
                        break;
        			case 'property_types':
                        $document->loanPropertyPivot()->sync($dataType);
                        break;
    				case 'occupancy_statuses':
    				    $document->occStatusPivot()->sync($dataType);
    				case 'locations':
    				    $document->locationPivot()->sync($dataType);
                        break;
        		}

            } catch (\Exception $e) {

                $message = $e->getMessage();
                $response = [
                   'success' => false,
                   'message' => $message
                ];

                return $response;
            }
    	}
        
        return ['success' => true];
    }


    /**
     * get global documnets by apprasial order id
     *
     * @param  int $id
     * @return array $documentsDataTables
     */
    public function globalDocumentsByApprasialOrder($id)
    {
        $orderApprasial = Order::find($id);

        if ($orderApprasial) {
            $apprasial = $orderApprasial->appraisalType;

            if ($apprasial) {

                return  $apprasial->apprTypeOrderDocument;
                
            } else {
                $response = [
                   'success' => false,
                   'message' => 'Apprasial not found'
                ];
            }
        } else {
            $response = [
               'success' => false,
               'message' => 'Apprasial Order not found'
            ];
        }

        return $response;
    }

}