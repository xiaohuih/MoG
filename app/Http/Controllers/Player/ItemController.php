<?php

namespace App\Http\Controllers\Player;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\Controller;
use App\Models\Player;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Input;

class ItemController extends Controller
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
            ->header(trans('game.player'))
            ->description(trans('game.item_desc'))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Player\Item);
        // 新增按钮
        $grid->disableCreateButton();
        // 导出按钮
        $grid->disableExport();
        // 批量操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        // 筛选
        $grid->filter(function($filter){
            $filter->expand();
            $filter->like('name', trans('game.info.name'));
        });
        // 行操作
        $grid->disableActions();
        // $grid->actions(function ($actions) {
        //     $actions->disableEdit();
        //     $actions->disableView();
        //     $actions->disableDelete();
        //     $actions->append(new ConfirmButton($actions->getResource(), sprintf("%d_%d_%d_%d", $actions->row['player'], $actions->row['id'], $actions->row['configid'], $actions->row['count']), 'remove', 'fa-trash'));
        // });
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->configid(trans('game.info.configid'));
        $grid->count(trans('game.info.count'));
        $grid->is_setup(trans('game.info.is_setup'));

        return $grid;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $data 一行道具数据
     *
     * @return \Illuminate\Http\Response
     */
    public function update($data)
    {
        return $this->form()->update($data);
        // if (Input::get('remove')) {
        //     $params = explode("_", $data);
        //     return Player\Item::remove($params[0], $params[1], $params[2], $params[3]);
        // } else {
        //     return $this->form()->update($data);
        // }
    }
}
