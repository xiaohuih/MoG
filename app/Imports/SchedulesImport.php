<?php

namespace App\Imports;

use App\Models\Schedule;
use Maatwebsite\Excel\Concerns\ToModel;

class SchedulesImport implements ToModel
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
            'begin_relativetime'    => $row['begin_relativetime'],
            'end_relativetime'      => $row['end_relativetime'],
            'begin_date'            => $row['begin_date'],
            'end_date'              => $row['end_date'],
            'begin_time'            => $row['begin_time'],
            'duration'              => $row['duration'],
            'interval'              => $row['interval'],
            'wdays'                 => $row['wdays'],
        ]);
    }
}
