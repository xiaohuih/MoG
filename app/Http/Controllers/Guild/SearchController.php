<?php

namespace App\Http\Controllers\Guild;

use App\Http\Controllers\Controller;
use App\Models\Guild;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Input;

class SearchController extends Controller
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
            ->header(trans('game.guild'))
            ->description(trans('admin.search'))
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
            ->header(trans('game.guild'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id));
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Guild\Search);
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
        $grid->flag(trans('game.info.flag'));
        $grid->declaration(trans('game.info.declaration'))->editable();
        $grid->joinlimit(trans('game.info.joinlimit'));
        $grid->membercount(trans('game.info.membercount'));
        $grid->leader(trans('game.info.leader'))->display(function ($link) {
            return "<a href='/admin/player/search?&id={$link}'>{$link}</a>";
        });
        $grid->fund(trans('game.info.fund'));
        $grid->exp(trans('game.info.exp'));

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
        return Guild::disband($id);
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
        $name = Input::get('name');
        $value = Input::get('value');

        return Guild::mofify($id, $name, $value);
    }
}
