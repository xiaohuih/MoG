<?php

namespace App\Exports;

class SchedulesExport extends BaseExport
{
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
}
