<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

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
            ->description(trans('game.pet'))
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
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableEdit();
        });
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->level(trans('game.info.level'));
        //$grid->power(trans('game.info.power'));
        $grid->nature(trans('game.info.nature'));
        $grid->star(trans('game.info.star'));
        $grid->quality(trans('game.info.quality'));

        return $grid;
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($this->form()->destroy($id)) {
            $data = [
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ];
        } else {
            $data = [
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ];
        }

        return response()->json($data);
    }
}
