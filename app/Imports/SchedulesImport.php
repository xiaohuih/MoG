<?php

namespace App\Imports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SchedulesImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Schedule([
            'id'                    => $row['id'],
            'name'                  => $row['name'],
            'begin_relativetime'    => empty($row['begin_relativetime']) ? null : $row['begin_relativetime'],
            'end_relativetime'      => empty($row['end_relativetime']) ? null : $row['end_relativetime'],
            'begin_date'            => empty($row['begin_date']) ? null : $row['begin_date'],
            'end_date'              => empty($row['end_date']) ? null : $row['end_date'],
            'begin_time'            => empty($row['begin_time']) ? null : $row['begin_time'],
            'duration'              => $row['duration'],
            'interval'              => $row['interval'],
            'wdays'                 => $row['wdays'],
        ]);
    }
}
