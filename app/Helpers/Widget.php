<?php
namespace App\Helpers;

class Widget
{
    public static function multiselect($collection, $fields, $key = 'id')
    {
        $fields = collect($fields);
        $columns = collect([]);
        $data = $collection->keyBy($key)->map(function ($item) use ($fields, $columns) {
            foreach ($fields as $field) {
                $columns = $columns->merge($item->$field);
            }
            return $columns->implode(' ');
        });
        return $data;
    }
}