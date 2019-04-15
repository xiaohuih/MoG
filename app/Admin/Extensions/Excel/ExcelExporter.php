<?php

namespace App\Admin\Extensions\Excel;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExporter extends AbstractExporter
{
    /**
     * @var object
     */
    protected $export;
    /**
     * The extension of file to export.
     *
     * @var string
     */
    protected $ext;
    
    /**
     * Create a new exporter instance.
     *
     * @param $export
     * @param $ext
     */
    public function __construct($export, $ext = 'xlsx')
    {
        $this->export = $export;
        $this->ext = $ext;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $filename = $this->getTable().'.'.$this->ext;
        $export = new $this->export($this->getData(false));
        
        Excel::download($export, $filename)->send();
        exit;
    }
}
