<?php

namespace App\Admin\Extensions;

use Closure;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Encore\Admin\Form\Field;
use Encore\Admin\Form;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SchedulesImport;

class ImportForm extends Form
{
    /**
     * To trans Eloquent model.
     *
     * @var ToModel
     */
    protected $toModelClass;

    /**
     * Create a new form instance.
     *
     * @param $toModelClass
     * @param $model
     * @param \Closure $callback
     */
    public function __construct($toModelClass, $model, Closure $callback = null)
    {
        parent::__construct($model, $callback);

        $this->toModelClass = $toModelClass;
    }

    /**
     * Default storage for file to upload.
     *
     * @return mixed
     */
    public function defaultStorage()
    {
        return config('admin.upload.disk');
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

        $this->import($this->updates);

        if (($response = $this->callSaved()) instanceof Response) {
            return $response;
        }

        if ($response = $this->ajaxResponse(trans('admin.save_succeeded'))) {
            return $response;
        }

        return $this->redirectAfterStore();
    }

    /**
     * Import files.
     *
     * @param array $columns
     * 
     * @return mixed
     */
    protected function import($columns)
    {
        foreach ($columns as $column => $value) {
            if (!$value instanceof UploadedFile) {
                continue;
            }
            Excel::import(new $this->toModelClass, $value);
        }
    }
}