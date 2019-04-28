<?php

namespace App\Http\Controllers;

use App\Models\Mail;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;

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
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('game.mail'))
            ->description(trans('admin.detail'))
            ->body($this->detail($id));
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
            $filter->between('sendtime', trans('game.info.sendtime'))->datetime();
            $filter->between('created_at', trans('admin.created_at'))->datetime();
        });
        // 列
        $grid->id('ID');
        $grid->title(trans('game.info.title'));
        $grid->content(trans('game.info.content'));
        $grid->attachments(trans('game.info.attachments'));
        $grid->type(trans('game.info.type'))->display(function ($type) {
            if ($type == Mail::TYPE_GLBOAL) {
                return rans('game.info.globalmail');
            } else if ($type == Mail::TYPE_NORMAL) {
                return trans('game.info.normalmail');
            }
            return 'unknown';
        });
        $grid->receivers(trans('game.info.receivers'));
        $grid->zones(trans('game.info.zone'));
        $grid->sendtime(trans('game.info.sendtime'))->sortable();
        $grid->created_at(trans('admin.created_at'))->sortable();

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
        $show = new Show(Mail::findOrFail($id));
        $show->id('ID');
        $show->title(trans('game.info.title'));
        $show->content(trans('game.info.content'));
        $show->attachments(trans('game.info.attachments'));
        $show->type(trans('game.info.type'));
        $show->receivers(trans('game.info.receivers'));
        $show->zones(trans('game.info.zone'));
        $show->sendtime(trans('game.info.sendtime'));
        $show->created_at(trans('admin.created_at'));
        $show->updated_at(trans('admin.updated_at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Mail);
        $form->display('id');
        $form->select('type', trans('game.info.type'))->options([
            Mail::TYPE_GLBOAL => trans('game.info.globalmail'), 
            Mail::TYPE_NORMAL => trans('game.info.normalmail')
        ])->rules('required');
        $form->text('receivers', trans('game.info.receivers'));
        $form->text('title', trans('game.info.title'))->rules('required|max:30');
        $form->textarea('content', trans('game.info.content'))->rows(3)->rules('required|max:255');
        $form->textarea('attachments', trans('game.info.attachments'))->rows(3)->rules('required|max:255');
        $form->text('zones', trans('game.info.zone'));
        $form->datetime('sendtime', trans('game.info.sendtime'));
        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
