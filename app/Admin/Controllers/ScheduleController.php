<?php

namespace App\Admin\Controllers;

use App\Models\Schedule;
use App\Exports\SchedulesExport;
use App\Imports\SchedulesImport;
use App\Admin\Extensions\ImportForm;
use App\Admin\Extensions\ExcelExpoter;
use App\Admin\Extensions\Grid\CreateButton;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form\Builder;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Exporters\CsvExporter;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Facades\Admin;

class ScheduleController extends Controller
{
    use HasResourceActions;

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
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('game.schedules'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id));
    }

    /**
     * Edit interface.
     *
     * @param mixed   $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('game.schedules'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('game.schedules'))
            ->description(trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Import interface.
     *
     * @return Content
     */
    public function importget(Content $content)
    {
        return $content
            ->header(trans('game.schedules'))
            ->description(trans('admin.import'))
            ->body($this->form_import());
    }

    /**
     * Import interface.
     *
     * @return Content
     */
    public function importpost()
    {
        return $this->form_import()->store();
    }

    /**
     * Get current resource route url.
     *
     * @param int $slice
     *
     * @return string
     */
    public function resource($slice = -2)
    {
        $segments = explode('/', trim(app('request')->getUri(), '/'));

        if ($slice != 0) {
            $segments = array_slice($segments, 0, $slice);
        }

        return implode('/', $segments);
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Schedule);

        $grid->id('ID')->sortable();
        $grid->name();
        $grid->begin_relativetime();
        $grid->end_relativetime();
        $grid->begin_date()->display(function ($begin_date) {
            return $begin_date ?: '0';
        });
        $grid->end_date()->display(function ($end_date) {
            return $end_date ?: '0';
        });
        $grid->begin_time();
        $grid->duration();
        $grid->interval();
        $grid->wdays();
        $grid->exporter(new ExcelExpoter());
        
        $grid->disableCreation();
        $grid->tools(function ($tools) {
            $tools->prepend(new CreateButton());
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed   $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Schedule::findOrFail($id));

        $show->id('ID');
        $show->name();
        $show->begin_relativetime();
        $show->end_relativetime();
        $show->begin_date()->as(function ($begin_date) {
            return $begin_date ?: '0';
        });
        $show->end_date()->as(function ($end_date) {
            return $end_date ?: '0';
        });
        $show->begin_time();
        $show->duration();
        $show->interval();
        $show->wdays();

        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Schedule);

        $form->display('id', 'ID');

        $form->text('name');
        $form->text('begin_relativetime');
        $form->text('end_relativetime');
        $form->date('begin_date');
        $form->date('end_date');
        $form->time('begin_time');
        $form->text('duration');
        $form->text('interval');
        $form->text('wdays');

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }

    /**
     * Make a import form builder.
     *
     * @return Form
     */
    protected function form_import() 
    {
        $form = new ImportForm(new Schedule);
        $form->disableViewCheck();
        $form->disableEditingCheck();
        $form->disableCreatingCheck();

        $form->setAction($form->resource(0));
        $form->setTitle(trans('admin.import'));
        $form->file('importfile')->uniqueName()->rules('required|mimes:csv,xlsx');

        return $form;
    }
}
