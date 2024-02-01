<?php

namespace iteos\Exports;

use iteos\Models\UomValue;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UomExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'id',
            'UOM Name',
        ];
    }

    public function collection()
    {
        return UomValue::where('deleted_at',NULL)->select('id','name')->get();
    }
}
