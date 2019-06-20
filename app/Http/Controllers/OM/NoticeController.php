<?php

namespace App\Http\Controllers\OM;

use App\Admin\Extensions\Grid\SwitchDisplay;
use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Models\OM;

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
        $grid = new Grid(new OM\Notice);
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
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });
        // 列
        $grid->type('ID');
        $grid->column(trans('game.info.type'))->display(function () {
            return trans('game.notices.'.OM\Notice::$types[$this->type]['name']);
        })->label('success');
        $grid->title(trans('game.info.title'));
        $grid->content(trans('game.info.content'))->limit(150);
        $grid->show(trans('game.info.state'))->display(function ($value, $column) {
            if (true == OM\Notice::$types[$this->type]['switch']) {
                return $column->displayUsing(SwitchDisplay::Class);
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
        $form = new Form(new OM\Notice);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });
        $form->disableCreatingCheck();
        
        $form->display('type', 'ID');
        $form->text('title', trans('game.info.title'))->rules('required|max:128');
        $form->textarea('content', trans('game.info.content'))->rows(5)->rules('required|max:4096');

        return $form;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(OM\Notice::findToShow($id));
        // 工具
        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });

        $show->type('ID');
        $show->title(trans('game.info.title'));
        $show->content(trans('game.info.content'));

        return $show;
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
        $show = Input::get('show');
        if ($show) {
            return OM\Notice::setProperty($id, 'show', $show == 'on' ? 1 : 0);
        } else {
            return $this->form()->update($id);
        }
    }
}
