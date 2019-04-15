<?php

namespace App\Admin\Extensions\Excel;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\UploadedFile;

class ExcelImporter
{
    /**
     * @var object
     */
    protected $import;

    /**
     * Create a new exporter instance.
     *
     * @param $import
     */
    public function __construct($import)
    {
        $this->import = $import;
    }
    
    /**
     * Import data from file.
     *
     * @param UploadedFile $file
     * 
     */
    public function import($file)
    {
        Excel::import(new $this->import, $file);
    }
}
