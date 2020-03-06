<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Input;
use App\Models\Player;
use Encore\Admin\Show;

class PlayerController extends Controller
{
    /**
     * 玩家状态
     */
    public static $statusInfo = [
        0 => ['name' => 'offline', 'style' => 'default'],    // 离线
        1 => ['name' => 'online', 'style' => 'success'],     // 在线
    ];

    /**
     * 可操作玩家行为
     */
    public static $forbidActions = ['forbidlogin', 'forbidchat', 'kickout'];

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    public static function detail($id)
    {
        $show = new Show(Player::findOrFail($id));
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
        $show->hint(trans('game.helps.remove_value_hint'));
        $show->gold(trans('game.info.gold'));
        $show->diamond(trans('game.info.diamond'));
        $show->exp(trans('game.info.exp'));
        $show->status(trans('game.info.status'))->unescape()->as(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.online');
                return "<span class='label label-success'>$name</span>";
            } else {
                $name = trans('game.info.offline');
                return "<span class='label label-default'>$name</span>";
            }
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
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function update($id)
    {
        $data = Input::all();

        foreach ($data as $key => $value) {
            if (in_array($key, self::$forbidActions)) {
                return Player::perform($id, $key, $value);
            }
        }
    }
}
