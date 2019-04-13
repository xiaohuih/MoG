<?php

namespace App\Admin\Extensions;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FromCollectionExport implements FromCollection, WithHeadings
{
    protected $collection;

    /**
     * Create a new instance.
     *
     * @param \Illuminate\Support\ $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'id',
            'name',
            'begin_relativetime',
            'end_relativetime',
            'begin_date',
            'end_date',
            'begin_time',
            'duration',
            'interval',
            'wdays',
            'created_at',
            'updated_at',
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->collection;
    }
}
