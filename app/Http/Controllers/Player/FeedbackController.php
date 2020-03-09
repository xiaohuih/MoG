<?php

namespace App\Http\Controllers\Player;

use App\Models\Player\Feedback;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;

class FeedbackController extends Controller
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
            ->header(trans('game.feedback'))
            ->description(trans('game.helps.feedback_desc'))
            ->body($this->grid());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Feedback);

        // 禁用新增按钮
        $grid->disableCreateButton();
        // 批量操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        // 没有数据库表,不能筛选或者操作数据
        $grid->disableFilter();
        $grid->disableActions();
        // 列
        $grid->id();
        $grid->type(trans('game.info.type'))->display(function ($type) {
            if ($type == 1) {
                $name = trans('game.info.bug');
                return "<span class='label label-success'>$name</span>";
            } else if ($type == 2) {
                $name = trans('game.info.advice');
                return "<span class='label label-default'>$name</span>";
            }
        });
        $grid->charid(trans('game.player'));
        $grid->nickname(trans('game.info.name'));
        $grid->content(trans('game.info.content'));
        $grid->phone(trans('game.info.phone'));
        $grid->qq();
        $grid->timestamp(trans('game.info.createtime'));

        return $grid;
    }

}
