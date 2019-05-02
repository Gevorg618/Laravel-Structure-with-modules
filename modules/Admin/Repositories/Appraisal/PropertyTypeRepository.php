<?php

namespace Modules\Admin\Repositories\Appraisal;

use App\Models\Customizations\PropertyType;

class PropertyTypeRepository
{
    private $PropertyType;

    /**
     * PropertyTypesRepository constructor.
     */
    public function __construct()
    {
        $this->propertyType = new PropertyType();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function propertyTypes()
    {
        return $this->propertyType->get();
    }


    /**
     * @return mixed
     */
    public function propertyTypesList()
    {
        return $this->propertyType->select('id', 'descrip')->orderBy('descrip', 'ASC')->get();
    }


    /**
     * @param $ids
     * @return mixed
     */
    public function propertyTypesListAddRemoveFromClient($ids)
    {
        return $this->propertyType->orderBy('descrip', 'asc')
            ->whereIn('id', $ids)->pluck('descrip','id')->toArray();
    }
}
