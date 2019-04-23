<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Models\Player;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class SearchController extends Controller
{
    use HasResourceActions;

    /**
     * Player status
     */
    protected $statusInfo = [
        0 => ['name' => 'offline', 'style' => 'default'],    // 在线
        1 => ['name' => 'online', 'style' => 'success'],     // 离线
        2 => ['name' => 'forbid', 'style' => 'danger']       // 封禁
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
            ->header(trans('game.players'))
            ->description(trans('admin.list'))
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
            ->header(trans('game.players'))
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
        $grid = new Grid(new Player);
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
            $filter->like('name', 'name');
        });
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
        });
        // 列
        $grid->id('ID');
        $grid->name(trans('game.player.name'));
        //$grid->accname(trans('game.player.account'));
        $grid->level(trans('game.player.level'));
        $grid->power(trans('game.player.power'));
        $grid->vip(trans('game.player.vip'));
        // $grid->gold(trans('game.player.gold'));
        // $grid->diamond(trans('game.player.diamond'));
        $grid->guild(trans('game.player.guild'));
        // $grid->column('createtime', trans('game.player.createtime'))->display(function ($createtime) {
        //     return date('Y-m-d H:i:s', $createtime);
        // });
        // $grid->column('offlinetime', trans('game.player.offlinetime'))->display(function ($offlinetime) {
        //     return date('Y-m-d H:i:s', $offlinetime);
        // });
        $statusInfo = $this->statusInfo;
        $grid->status(trans('game.player.status'))->display(function ($status) use ($statusInfo) {
            $status = $statusInfo[$status];

            $name = trans('game.player.'.$status['name']);
            $style = $status['style'];
            return "<span class='label label-{$style}'>$name</span>";
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
        $show = new Show(Player::findOrFail($id));
        // 工具
        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableList();
            $tools->disableDelete();
        });
        $show->id('ID');
        $show->name(trans('game.player.name'));
        $show->accname(trans('game.player.account'));
        $show->guild(trans('game.player.guild'));
        $show->level(trans('game.player.level'));
        $show->power(trans('game.player.power'));
        $show->vip(trans('game.player.vip'));
        $show->paper_level(trans('game.player.paperlevel'));
        $show->diamond(trans('game.player.diamond'));
        $show->gold(trans('game.player.gold'));
        $show->diamond(trans('game.player.diamond'));
        $show->exp(trans('game.player.exp'));
        $statusInfo = $this->statusInfo;
        $show->status(trans('game.player.status'))->unescape()->as(function ($status) use ($statusInfo) {
            $status = $statusInfo[$status];

            $name = trans('game.player.'.$status['name']);
            $style = $status['style'];
            return "<span class='label label-{$style}'>$name</span>";
        });
        $show->createtime(trans('game.player.createtime'))->as(function ($createtime) {
            return date('Y-m-d H:i:s', $createtime);
        });
        $show->offlinetime(trans('game.player.offlinetime'))->as(function ($offlinetime) {
            return date('Y-m-d H:i:s', $offlinetime);
        });

        return $show;
    }
}
