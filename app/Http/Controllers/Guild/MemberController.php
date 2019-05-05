<?php

namespace App\Http\Controllers\Guild;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\PlayerController;
use App\Http\Controllers\Controller;
use App\Models\Guild;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;

class MemberController extends Controller
{
    use HasResourceActions;
    /**
     * 职位
     */
    protected static $authorities = [
        1 => ['name' => 'member'],                            // 成员
        2 => ['name' => 'elite', 'style' => 'success'],       // 精英
        3 => ['name' => 'viceleader', 'style' => 'primary'],  // 副会长
        4 => ['name' => 'leader', 'style' => 'warning'],      // 会长
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
            ->header(trans('game.guild'))
            ->description(trans('game.info.member'))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Guild\Member);
        // 新增按钮
        $grid->disableCreateButton();
        // 导出按钮
        $grid->disableExport();
        // 筛选
        $grid->filter(function($filter){
            $filter->expand();
            $filter->like('name', trans('game.info.name'));
        });
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableEdit();
            $actions->disableView();
            $actions->disableDelete();
            $actions->append(new ConfirmButton($actions->getResource(), sprintf("%d_%d", $actions->row['guild'], $actions->getRouteKey()), 'kick', 'fa-trash'));
        });
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'));
        $authorities = self::$authorities;
        $grid->authority(trans('game.info.authority'))->display(function ($authority) use ($authorities) {
            $authority = $authorities[$authority];
            $name = trans('game.info.'.$authority['name']);
            if (isset($authority['style'])) {
                $style = $authority['style'];
                return "<span class='label label-{$style}'>$name</span>";
            } else {
                return $name;
            }
        });
        $grid->level(trans('game.info.level'));
        $grid->role(trans('game.info.role'));
        $grid->power(trans('game.info.power'));
        $grid->weekly_contribution(trans('game.info.weekly_contribution'));
        $grid->total_contribution(trans('game.info.total_contribution'));
        $grid->status(trans('game.info.status'))->display(function ($status) {
            $status = PlayerController::$statusInfo[$status];

            $name = trans('game.info.'.$status['name']);
            $style = $status['style'];
            return "<span class='label label-{$style}'>$name</span>";
        });

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
        $grid = $this->grid();
        $data = [
            'status'  => true,
            'message' => trans('admin.delete_succeeded'),
        ];
        return response()->json($data);
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
        $params = explode("_", $id);
        return Guild::removeMember($params[0], $params[1]);
    }
}
