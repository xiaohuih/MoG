<?php

namespace App\Admin\Extensions;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\UploadedFile;
use Encore\Admin\Form;
use Maatwebsite\Excel\Facades\Excel;

class ImportForm extends Form
{
    /**
     * Import driver.
     *
     * @var string
     */
    protected $importer;

    /**
     * Set importer driver to export.
     *
     * @param $importer
     *
     * @return $this
     */
    public function importer($importer)
    {
        $this->importer = $importer;

        return $this;
    }

    /**
     * Store a new record.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $data = Input::all();

        // Handle validation errors.
        if ($validationMessages = $this->validationMessages($data)) {
            return back()->withInput()->withErrors($validationMessages);
        }

        if (($response = $this->prepare($data)) instanceof Response) {
            return $response;
        }

        foreach ($this->updates as $field => $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }
            $this->importer->import($file);
        }

        if (($response = $this->callSaved()) instanceof Response) {
            return $response;
        }

        if ($response = $this->ajaxResponse(trans('admin.save_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterStore();
    }
}