<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\CsvExporter;
use Illuminate\Support\Collection;

class TableExpoter extends CsvExporter
{
    /**
     * @param Collection $records
     *
     * @return array
     */
    public function getHeaderRowFromRecords(Collection $records): array
    {
        $titles = collect(array_dot($records->first()->toArray()))->keys();

        return $titles->toArray();
    }
}
