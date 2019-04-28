<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;

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
     * Show interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function show($id, Content $content)
    {
        return $content
            ->header(trans('game.notice'))
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
            $filter->between('created_at', trans('admin.created_at'))->datetime();
        });
        // 列
        $grid->id('ID');
        $grid->content(trans('game.info.content'));
        $grid->starttime(trans('game.info.starttime'))->sortable();
        $grid->endtime(trans('game.info.endtime'))->sortable();
        $grid->interval(trans('game.info.interval'));
        $grid->zones(trans('game.info.zone'));
        $grid->created_at(trans('admin.created_at'))->sortable();
        $grid->status(trans('game.info.status'))->switch();

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
        $show = new Show(Notice::findOrFail($id));
        $show->id('ID');
        $grid->content(trans('game.info.content'));
        $grid->starttime(trans('game.info.starttime'));
        $grid->endtime(trans('game.info.endtime'));
        $grid->interval(trans('game.info.interval'));
        $grid->zones(trans('game.info.zone'));
        $grid->status(trans('game.info.status'));
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
        $form = new Form(new Notice);
        $form->display('id');

        $form->text('content', trans('game.info.content'))->rules('required|max:255');
        $form->datetime('starttime', trans('game.info.starttime'));
        $form->datetime('endtime', trans('game.info.endtime'));
        $form->text('interval', trans('game.info.interval'));
        $form->text('zones', trans('game.info.zone'));

        $form->display('created_at', trans('admin.created_at'));
        $form->display('updated_at', trans('admin.updated_at'));

        return $form;
    }
}
