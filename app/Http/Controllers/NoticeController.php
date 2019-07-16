<?php

namespace App\Http\Controllers;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Models\Notice;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;

class NoticeController extends Controller
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
            ->header(trans('game.notice'))
            ->description(trans('admin.list'))
            ->body($this->grid());
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->header(trans('game.notice'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->header(trans('game.notice'))
            ->description(trans('admin.create'))
            ->body($this->form());
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Notice);
        // 筛选
        $grid->filter(function($filter){
            $filter->like('content', trans('game.info.content'));
        });
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'send', 'fa-paper-plane'));
            if ($actions->row['status'] == 1) {
                $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'revoke', 'fa-reply'));
            }
        });
        // 倒序
        $grid->model()->orderBy('updated_at', 'desc');
        // 列
        $grid->id('ID');
        $grid->content(trans('game.info.content'))->display(function($text) {
            return str_limit($text, 100, '...');
        });
        $grid->starttime(trans('game.info.starttime'))->sortable();
        $grid->endtime(trans('game.info.endtime'))->sortable();
        $grid->interval(trans('game.info.interval'));
        $grid->zones(trans('game.info.zone'));
        $grid->status(trans('game.info.status'))->display(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.sent');
                return "<span class='label label-success'>$name</span>";
            } else {
                $name = trans('game.info.unsent');
                return "<span class='label label-default'>$name</span>";
            }
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Notice);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->display('id');

        $form->text('content', trans('game.info.content'))->rules('required|max:255');
        $form->datetime('starttime', trans('game.info.starttime'));
        $form->datetime('endtime', trans('game.info.endtime'));
        $form->text('interval', trans('game.info.interval'))->rules('required|regex:/^\d+$/')->help(trans('game.helps.interval'));
        $form->multipleSelect('zones', trans('game.info.zone'))->options('/admin/zones')->rules('required');

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
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
        if (Input::get('send')) {
            return Notice::find($id)->send();
        }
        else if (Input::get('revoke')) {
            return Notice::revoke($id);
        } else {
            return $this->form()->update($id);
        }
    }
}
