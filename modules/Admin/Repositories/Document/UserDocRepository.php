<?php


namespace Modules\Admin\Repositories\Document;
use App\Models\Documents\UserDoc;

class UserDocRepository
{
    /**
     * Object of UserData class
     *
     * @var $orderLocation
     */
    private $userDoc;

    /**
     * OrderLocationRepository constructor.
     */
    public function __construct()
    {
        $this->userDoc = new UserDoc();
    }


    /**
     * @return mixed
     */
    public function userDocs()
    {
        return $this->userDoc->get();
    }


    /**
     * @param $id
     * @return mixed
     */
    public function userDoc($id)
    {
        return $this->userDoc->find($id);
    }
}
