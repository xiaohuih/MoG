<?php

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $filename = $this->getTable().'.xlsx';
        $export = new FromCollectionExport($this->getData(false));
        
        Excel::download($export, $filename)->send();
        exit;
    }
}
