<?php

namespace Modules\Admin\Exports\Accounting\Payables;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;

class PayablesExport implements FromCollection, WithHeadings
{
    protected $data;
    protected $headings;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->headings = count($data) ? array_keys(reset($this->data)) : [];
    }

    public function headings(): array
    {
        return $this->headings;
    }

    public function collection()
    {
        return collect($this->data);
    }
}