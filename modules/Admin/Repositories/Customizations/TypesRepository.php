<?php

namespace Modules\Admin\Repositories\Customizations;

use App\Models\Customizations\Type;
use Yajra\DataTables\Datatables;

class TypesRepository
{
    private $type;

    /**
     * TypesRepository constructor.
     */
    public function __construct()
    {
        $this->type = new Type();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTypes()
    {
        return $this->type->all();
    }

    /**
     * create assoc array by types id
     *
     * @return array
     */
    public function createTypesArray()
    {
        $types = $this->getTypes();
        $typesArray = [];
        foreach ($types as $type) {
            $typesArray[$type->id] = $type->form ? ($type->form . ' - ' . $type->descrip) : $type->descrip;
        }

        return $typesArray;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getTypesForMultiSelect()
    {
        return Type::select(\DB::raw("CONCAT(form,'-',descrip) as concat, id"))
            ->orderBy(\DB::raw("CONCAT(form,'',descrip)"))->pluck('concat', 'id');
    }

    /**
     * @param $heads
     * @return array
     */
    public function getTypesForSampleTemplate($heads)
    {
        $types = $this->createTypesArray();
        $lines[] = $heads;
        foreach ($types as $typeId => $type) {
            $lines[] = [
                $typeId.'|'.$type,
                "0.00",
                "0.00",
            ];
        }

        return $lines;
    }


    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getTypesCount()
    {
        return $this->type->count();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function create($data)
    {
        return $this->type->create($data);
    }

    /**
     *  Get type by id
     *  
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getOne($id)
    {
        return $this->type->findOrFail($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function update($id, $data)
    {
        return $this->type->where('id', $id)->update($data);
    }

    /**
     * return all types 
     * 
     * @return array
     */
    public function typesDataTables()
    {

        $types = $this->type::query();

        return  Datatables::of($types)
                ->editColumn('code', function ($type) {
                    return $type->code;
                })
                ->editColumn('descrip', function ($type) {
                    return $type->descrip;
                })
                ->editColumn('short_descrip', function ($type) {
                    return $type->short_descrip;
                })
                ->editColumn('form', function ($type) {
                        return  $type->form;
                })
                ->editColumn('ead_form', function ($type) {
                        return  $type->ead_form;
                })
                ->editColumn('mismo_label', function ($type) {
                        return $type->mismo_label;
                })
                ->editColumn('fha', function ($type) {
                        return $type->fha;
                })
                ->editColumn('active', function ($type) {
                        return $type->active;
                })
                ->editColumn('options', function ($type) {
                    return view('admin::appraisal.types.partials._options', compact('type'))->render();
                })
                ->rawColumns(['options'])
                ->make(true);
    }
}