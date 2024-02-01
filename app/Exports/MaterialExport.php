<?php

namespace iteos\Exports;

use iteos\Models\MaterialGroup;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MaterialExport implements FromCollection, WithHeadings

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'id',
            'Material Name',
        ];
    }

    public function collection()
    {
        return MaterialGroup::where('deleted_at',NULL)->select('id','material_name')->get();
    }
}
