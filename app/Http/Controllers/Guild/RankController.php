<?php

namespace App\Http\Controllers\Guild;

use App\Http\Controllers\Controller;
use App\Models\Guild;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class RankController extends Controller
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
            ->description(trans('game.rank'))
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
        $grid = new Grid(new Guild\Rank);
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
        $grid->disableFilter();
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
        });
        // 列
        $grid->rank(trans('game.info.rank'));
        $grid->id('ID')->display(function ($link) {
            return "<a href='search?&id={$link}'>{$link}</a>";
        });
        $grid->name(trans('game.info.name'));
        $grid->level(trans('game.info.level'));
        $grid->flag(trans('game.info.flag'));
        $grid->membercount(trans('game.info.membercount'))->display(function ($link) {
            return "<a href='/admin/guild/members?&id={$this->id}'>{$link}</a>";
        });
        $grid->leader(trans('game.info.leader'))->display(function ($link) {
            return "<a href='/admin/player/search?&id={$link}'>{$link}</a>";
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
        $show = new Show(Guild::findOrFail($id));
        // 工具
        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableList();
            $tools->disableDelete();
        });
        $show->id('ID');
        $show->name(trans('game.info.name'));
        $show->accname(trans('game.info.account'));
        $show->guild(trans('game.info.guild'));
        $show->level(trans('game.info.level'));
        $show->power(trans('game.info.power'));
        $show->vip(trans('game.info.vip'));
        $show->paper_level(trans('game.info.paperlevel'));
        $show->diamond(trans('game.info.diamond'));
        $show->gold(trans('game.info.gold'));
        $show->diamond(trans('game.info.diamond'));
        $show->exp(trans('game.info.exp'));
        $statusInfo = $this->statusInfo;
        $show->status(trans('game.info.status'))->unescape()->as(function ($status) use ($statusInfo) {
            $status = $statusInfo[$status];

            $name = trans('game.info.'.$status['name']);
            $style = $status['style'];
            return "<span class='label label-{$style}'>$name</span>";
        });
        $show->createtime(trans('game.info.createtime'))->as(function ($createtime) {
            return date('Y-m-d H:i:s', $createtime);
        });
        $show->offlinetime(trans('game.info.offlinetime'))->as(function ($offlinetime) {
            return date('Y-m-d H:i:s', $offlinetime);
        });

        return $show;
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
}
