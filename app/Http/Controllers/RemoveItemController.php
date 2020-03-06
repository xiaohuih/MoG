<?php

namespace App\Http\Controllers;

use App\Models\RemoveItem;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use App\Admin\Extensions\Grid\ConfirmButton;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;
use Encore\Admin\Facades\Admin;

class RemoveItemController extends Controller
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
            ->header(trans('game.remove_item'))
            ->description(trans('game.hint.remove_item_hint'))
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
            ->header('Detail')
            ->description('description')
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
            ->header('Edit')
            ->description('description')
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
            ->header('Create')
            ->description('description')
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     * 内容显示 主体表格
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RemoveItem);
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            if (Admin::user()->can('remove_item.approval') && ($actions->row['status'] == 0)){
                $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'approval', 'fa-paper-plane'));
            }
        });
        // 筛选
        $grid->filter(function($filter){
            // 去掉默认的id过滤器
            $filter->disableIdFilter();
            // 在这里添加字段过滤器
            $filter->like('player', trans('game.player'));
            $filter->like('configid', trans('game.info.configid'));
            $filter->between('count', trans('game.info.count'));
        });
        // 列
        $grid->id('Id');
        $grid->player(trans('game.player'));
        $grid->zone(trans('game.zone'));
        $grid->itemid(trans('game.info.itemid'));
        $grid->configid(trans('game.info.configid'));
        $grid->count(trans('game.info.count'));
        $grid->status(trans('game.info.status'))->display(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.sent');
                return "<span class='label label-success'>$name</span>";
            } else if ($status == 0) {
                $name = trans('game.info.unapproved');
                return "<span class='label label-default'>$name</span>";
            }
        });
        $grid->updated_at(trans('game.update_time'));

        return $grid;
    }

    
    /**
     * Make a form builder.
     * 新增和编辑界面
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new RemoveItem);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->text('player', trans('game.player'))->rules(['required', 'regex:/^(\*|\d{1,})(;\d{1,}){0,}$/']);
        $form->text('zone', trans('game.zone'))->rules(['required', 'regex:/^(\*|\d{1,})(;\d{1,}){0,}$/']);
        $form->text('itemid', trans('game.info.itemid'))->help(trans('game.helps.remove_item'))->rules(['required', 'regex:/^(\*|\d{1,})(;\d{1,}){0,}$/']);
        $form->text('configid', trans('game.info.configid'))->rules(['required', 'regex:/^(\*|\d{1,})(;\d{1,}){0,}$/'])->help(trans('game.helps.remove_item_config'));
        $form->text('count', trans('game.info.count'))->rules(['required', 'regex:/^(\*|\d{1,})(;\d{1,}){0,}$/'])->help(trans('game.helps.remove_item_count'));

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
        if (Input::get('approval')) {
            return RemoveItem::find($id)->send();
        } else {
            return $this->form()->update($id);
        }
    }
}
