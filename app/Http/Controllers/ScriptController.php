<?php

namespace App\Http\Controllers;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Models\Script;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;

class ScriptController extends Controller
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
        $grid = new Grid(new Script);
        // 筛选
        $grid->filter(function($filter){
            $filter->like('name', trans('game.info.name'));
        });
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'perform', 'fa-paper-plane'));
        });
        // 倒序
        $grid->model()->orderBy('id', 'desc');
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->content(trans('game.info.content'))->editable();
        $grid->server(trans('game.info.server'))->using(Script::$servers)->label('success');
        $grid->zones(trans('game.info.zone'));

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Script);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->display('id');

        $form->text('name', trans('game.info.name'))->rules('required|max:50');
        $form->textarea('content', trans('game.info.content'))->rows(3)->rules('required|max:255');
        $form->multipleSelect('zones', trans('game.info.zone'))->options('/admin/zones')->rules('required');
        $form->select('server', trans('game.info.server'))->options(Script::$servers)->rules('required');

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
        if (Input::get('perform')) {
            return Script::find($id)->perform();
        } else {
            return $this->form()->update($id);
        }
    }
}
