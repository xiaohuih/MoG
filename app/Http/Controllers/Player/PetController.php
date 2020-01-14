<?php

namespace App\Http\Controllers\Player;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\Controller;
use App\Models\Player;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Support\Facades\Input;

class PetController extends Controller
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
            ->description(trans('game.pet_desc'))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Player\Pet);
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
        // $grid->actions(function ($actions) {
        //     $actions->disableEdit();
        //     $actions->disableView();
        //     $actions->disableDelete();
        //     $actions->append(new ConfirmButton($actions->getResource(), sprintf("%d_%d", $actions->row['player'], $actions->getRouteKey()), 'remove', 'fa-trash'));
        // });
        $grid->disableActions();
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->level(trans('game.info.level'));
        $grid->nature(trans('game.info.nature'));
        $grid->star(trans('game.info.star'));
        $grid->quality(trans('game.info.quality'));

        return $grid;
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
        if (Input::get('remove')) {
            $params = explode("_", $id);
            return Player\Pet::remove($params[0], $params[1]);
        } else {
            return $this->form()->update($id);
        }
    }
}
