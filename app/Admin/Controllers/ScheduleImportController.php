<?php

namespace App\Admin\Controllers;

use App\Models\Schedule;
use App\Admin\Extensions\ImportForm;
use App\Admin\Extensions\Excel\ExcelImporter;
use App\Imports\SchedulesImport;
use App\Http\Controllers\Controller;
use Encore\Admin\Layout\Content;

class ScheduleImportController extends Controller
{
    /**
     * Index interface.
     *
     * @param Content $content
     * @return Content
     */
    public function index(Content $content)
    {
        return $content
            ->header(trans('game.schedules'))
            ->description(trans('admin.import'))
            ->body($this->form());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new ImportForm(new Schedule);
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();
        $form->setAction($form->resource(0));
        $form->setTitle(trans('admin.import'));

        $form->file('imfile')->uniqueName();

        $form->importer(new ExcelImporter(SchedulesImport::class));

        return $form;
    }
}
