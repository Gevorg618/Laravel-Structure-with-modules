<?php

namespace Modules\Admin\Helpers;
use Maatwebsite\Excel\Facades\Excel as MaathExcel;

class Excel
{

    /**
     * handle file export
     *
     * @param $lines
     * @param $name
     * @param $ext
     *
     * @return array
     */
    public function handleExport($lines, $name, $ext)
    {
        try {
            MaathExcel::create($name, function ($excel) use ($lines, $name) {
                $excel->sheet($name, function ($sheet) use ($lines) {
                    $sheet->fromArray($lines,  null, 'A1', false, false);
                });
            })->export($ext);
        } catch (\Exception $exception) {
            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ];

            return $result;
        }
    }

    /**
     * load file data
     *
     * @param $file
     * @return array
     */
    public function loadFile($file)
    {
        try {
            $result = MaathExcel::load($file)->noHeading()->get()->toArray();

            return $result;
        } catch (\Exception $exception) {
            $result = [
                'success' => '0',
                'type'    => 'error',
                'message' => $exception->getMessage(),
            ];

            return $result;
        }
    }
}