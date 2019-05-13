<?php

namespace App\Http\Controllers\Player;

use App\Admin\Extensions\Grid\SwitchDisplay;
use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PlayerController;
use App\Models\Player;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

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
            ->header(trans('game.player'))
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
            ->header(trans('game.player'))
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
        $grid = new Grid(new Player\Search);
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
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'kickout', 'fa-chain-broken'));
        });
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $grid->level(trans('game.info.level'));
        $grid->power(trans('game.info.power'));
        $grid->vip(trans('game.info.vip'));
        $grid->guild(trans('game.info.guild'))->display(function ($link) {
            return "<a href='/admin/guild/search?&id={$link}'>{$link}</a>";
        });
        $grid->status(trans('game.info.status'))->display(function ($status) {
            $status = PlayerController::$statusInfo[$status];

            $name = trans('game.info.'.$status['name']);
            $style = $status['style'];
            return "<span class='label label-{$style}'>$name</span>";
        });
        $grid->forbidlogin(trans('game.info.forbid'))->displayUsing(SwitchDisplay::Class);

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
        return PlayerController::detail($id);
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
        return PlayerController::update($id);
    }
}
