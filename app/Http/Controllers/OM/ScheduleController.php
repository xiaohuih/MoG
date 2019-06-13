<?php

namespace App\Http\Controllers\OM;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;
use App\Models\OM\Schedule;
use Illuminate\Support\Facades\Date;
use Cron\CronExpression;

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
            ->header(trans('game.script'))
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('game.script'))
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
            ->header(trans('game.script'))
            ->description(trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Schedule);
        // 倒序
        $grid->model()->orderBy('updated_at', 'desc');
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->cmd(trans('game.info.cmd'));
        $grid->cron(trans('game.info.cron'))->display(function ($cron) {
            return '<span class="label label-success">'.$cron.'</span>';
        });
        $grid->column(trans('game.info.lastrun'))->display(function () {
            return Date::instance(CronExpression::factory(
                $this->cron
            )->getPreviousRunDate())->format('Y-m-d H:i');
        });
        $grid->column(trans('game.info.nextrun'))->display(function () {
            return Date::instance(CronExpression::factory(
                $this->cron
            )->getNextRunDate())->format('Y-m-d H:i');
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Schedule);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->display('id');

        $form->text('name', trans('game.info.name'))->rules('required|max:128');
        $form->text('cmd', trans('game.info.cmd'))->rules('required|max:256');
        $form->text('cron', trans('game.info.cron'))->rules('required|cron');
        
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
