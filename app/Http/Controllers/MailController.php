<?php

namespace App\Http\Controllers;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Models\Mail;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;
use Encore\Admin\Facades\Admin;

class MailController extends Controller
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
            ->header(trans('game.mail'))
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
            ->header(trans('game.mail'))
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
            ->header(trans('game.mail'))
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
        $grid = new Grid(new Mail);
        // 筛选
        $grid->filter(function($filter){
            $filter->like('title', trans('game.info.title'));
        });
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            if (Admin::user()->can('mail.approval') && ($actions->row['status'] == 0)){
                $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'approval', 'fa-paper-plane'));
            }
            if ($actions->row['status'] == 1) {
                $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'revoke', 'fa-reply'));
            }
        });
        // 倒序
        $grid->model()->orderBy('updated_at', 'desc');
        // 列
        $grid->id('ID');
        $grid->title(trans('game.info.title'));
        $grid->content(trans('game.info.content'))->display(function($text) {
            return str_limit($text, 100, '...');
        });
        $grid->attachments(trans('game.info.attachments'))->display(function($text) {
            return str_limit($text, 15, '...');
        });
        $options = collect(Mail::$types)->map(function ($item) {
            return trans('game.mails.' . $item);
        })->all();
        $grid->receivers(trans('game.info.receivers'))->display(function($text) {
            return str_limit($text, 15, '...');
        });
        $grid->zones(trans('game.info.zone'))->display(function($text) {
            return str_limit($text, 15, '...');
        });
        $grid->sendtime(trans('game.info.sendtime'))->sortable();
        $grid->status(trans('game.info.status'))->display(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.sent');
                return "<span class='label label-success'>$name</span>";
            } else if ($status == 0) {
                $name = trans('game.info.unapproved');
                return "<span class='label label-default'>$name</span>";
            } else if ($status == 2) {
                $name = trans('game.info.revoked');
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
        $form = new Form(new Mail);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $options = collect(Mail::$types)->map(function ($item) {
            return trans('game.mails.' . $item);
        })->all();
        $form->text('receivers', trans('game.info.receivers'))->rules(['required', 'regex:/^(\*|\d{1,})(;\d{1,}){0,}$/'])->help(trans('game.helps.receivers'));
        $form->text('title', trans('game.info.title'))->rules('required|max:30');
        $form->textarea('content', trans('game.info.content'))->rows(3)->rules('required|max:255');
        $form->textarea('attachments', trans('game.info.attachments'))->rows(3)->rules('nullable|max:255|regex:/^(\d{1,},\d{1,})(;(\d{1,},\d{1,})){0,}$/')->help(trans('game.helps.items'));
        $form->multipleSelect('zones', trans('game.info.zone'))->options('/admin/zones')->rules('required');
        $form->datetime('sendtime', trans('game.info.sendtime'))->help(trans('game.helps.sendtime'));
        
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
        if (Input::get('approval')) {
            return Mail::find($id)->send();
        }
        else if (Input::get('revoke')) {
            return Mail::find($id)->revoke();
        } else {
            return $this->form()->update($id);
        }
    }
}
