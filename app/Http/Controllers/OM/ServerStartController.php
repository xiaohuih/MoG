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
use App\Models\OM;

class ServerStartController extends Controller
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
            ->header(trans('game.startserver'))
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
            ->header(trans('game.startserver'))
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
            ->header(trans('game.startserver'))
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
        $grid = new Grid(new OM\ServerStart);
        // 导出按钮
        $grid->disableExport();
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'start', 'fa-paper-plane'));
        });
        // 倒序
        $grid->model()->orderBy('updated_at', 'desc');
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->zone(trans('game.info.zone'));
        $grid->starttime(trans('game.info.opentime'));
        $grid->status(trans('game.info.status'))->display(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.executed');
                return "<span class='label label-success'>$name</span>";
            } else {
                $name = trans('game.info.unexecuted');
                return "<span class='label label-default'>$name</span>";
            }
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
        $form = new Form(new OM\ServerStart);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->display('id');
        $form->text('name', trans('game.info.name'))->rules('required|max:30');
        $form->select('zone', trans('game.info.zone'))->options('/admin/om/server/list?state=5')->rules('required');
        $form->datetime('starttime', trans('game.info.opentime'))->help(trans('game.helps.opentime'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        if (Input::get('start')) {
            return OM\ServerStart::find($id)->start();
        } else {
            return $this->form()->update($id);
        }
    }
}
