<?php

namespace App\Http\Controllers;

use App\Models\GCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;

class GCodeController extends Controller
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
            ->header(trans('game.gcode'))
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
            ->header(trans('game.gcode'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id));
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
            ->header(trans('game.gcode'))
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
            ->header(trans('game.gcode'))
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
        $grid = new Grid(new GCode);
        // 批量操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        // 行操作
        $grid->actions(function ($actions) {
            $changable = !$actions->row->is_config;
            if (!$changable) {
                $actions->disableEdit();
                $actions->disableDelete();
            }
        });

        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->type(trans('game.info.type'))->display(function ($type) {
            if ($type == GCode::TYPE_NOLIMIT) {
                return trans('game.gcodes.nolimit');
            } else if ($type == GCode::TYPE_ONCE) {
                return trans('game.gcodes.once');
            }
            return 'unknown';
        });
        $grid->platform(trans('game.info.platform'));
        $grid->group(trans('game.info.group'));
        $grid->column('begintime', trans('game.info.begintime'))->display(function ($begintime) {
            return empty($begintime) ? '--' : date('Y-m-d H:i:s', $begintime);
        });
        $grid->column('endtime', trans('game.info.endtime'))->display(function ($endtime) {
            return empty($endtime) ? '--' : date('Y-m-d H:i:s', $endtime);
        });

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(GCode::findOrFail($id));
        $show->id('ID');
        $show->name(trans('game.info.name'));
        $show->type(trans('game.info.type'))->as(function ($type) {
            if ($type == GCode::TYPE_NOLIMIT) {
                return trans('game.gcodes.nolimit');
            } else if ($type == GCode::TYPE_ONCE) {
                return trans('game.gcodes.once');
            }
            return 'unknown';
        });
        $show->platform(trans('game.info.platform'));
        $show->group(trans('game.info.group'));
        $form->email(trans('game.info.email'));
        $show->begintime(trans('game.info.begintime'))->as(function ($begintime) {
            return empty($begintime) ? '--' : date('Y-m-d H:i:s', $begintime);
        });
        $show->endtime(trans('game.info.endtime'))->as(function ($endtime) {
            return empty($endtime) ? '--' : date('Y-m-d H:i:s', $endtime);
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new GCode);
        $form->text('name', trans('game.info.name'));
        $form->select('type', trans('game.info.type'))->options([
            GCode::TYPE_NOLIMIT => trans('game.gcodes.nolimit'), 
            GCode::TYPE_ONCE => trans('game.gcodes.once')
        ]);
        $form->text('platform', trans('game.info.platform'));
        $form->text('group', trans('game.info.group'));
        $form->text('email', trans('game.info.email'));
        $form->datetime('begintime', trans('game.info.begintime'))->customFormat(function ($begintime) {
            return empty($begintime) ? null : date('Y-m-d H:i:s', $begintime);
        });
        $form->datetime('endtime', trans('game.info.endtime'))->customFormat(function ($begintime) {
            return empty($begintime) ? null : date('Y-m-d H:i:s', $begintime);
        });

        return $form;
    }
}
