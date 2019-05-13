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

class RankController extends Controller
{
    use HasResourceActions;

    /**
     * Player ranks
     */
    protected $ranks = [
        1 => 'game.ranks.level',      // 等级排行榜  
        3 => 'game.ranks.power',      // 战力排行榜
        10 => 'game.ranks.recharge',  // 充值排行榜
        11 => 'game.ranks.consume',   // 消费排行榜
        7 => 'game.ranks.parkour',    // 跑酷排行榜
        5 => 'game.ranks.arena',      // 竞技场排行榜
    ];

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
        $grid = new Grid(new Player\Rank);
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
            $filter->disableIdFilter();
            $filter->equal('type', trans('game.rank'))->select(collect($this->ranks)->map(function ($item) {
                return trans($item);
            }));
        });
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableDelete();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'kickout', 'fa-chain-broken'));
        });
        // 列
        $grid->rank(trans('game.info.rank'));
        $grid->id('ID')->display(function ($link) {
            return "<a href='search?&id={$link}'>{$link}</a>";
        });;
        $grid->name(trans('game.info.name'));
        $grid->value(trans('game.info.value'));
        $grid->role(trans('game.info.role'));
        $grid->vip(trans('game.info.vip'));
        $grid->guild(trans('game.info.guild'))->display(function ($link) {
            return "<a href='/admin/guild/search?&name={$link}'>{$link}</a>";
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
