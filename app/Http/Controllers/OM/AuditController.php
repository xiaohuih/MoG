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

class AuditController extends Controller
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
            ->header(trans('game.audit'))
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
            ->header(trans('game.audit'))
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
            ->header(trans('game.audit'))
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
            ->header(trans('game.audit'))
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
        $grid = new Grid(new OM\Audit);
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
            $actions->disableView();
            $actions->disableDelete();
        });
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.title'));
        $grid->column('packages','包名')->map('ucwords')->implode(',');
      

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OM\Audit);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
            $tools->disableView();
        });
        $form->disableCreatingCheck();
        
        $form->display('id', 'ID');
        $form->text('name', trans('game.info.title'))->rules('required|max:128');
        $form->text('packages', trans('game.info.package'))->rules(['required', 'regex:/^((\w){1,}\.*){1,}\@{0,1}\w{1,}((,((\w){1,}\.*){1,})\@{0,1}\w{1,}){0,}$/']);

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
        $show = new Show(OM\Audit::findToShow($id));
        // 工具
        $show->panel()->tools(function ($tools) {
            $tools->disableDelete();
        });

        \Log::debug($show);

        $show->id('ID');
        $show->name(trans('game.info.title'));
        $show->packages(trans('game.info.content'));

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
            return OM\Audit::setProperty($id, 'show', $show == 'on' ? 1 : 0);
        } else {
            return $this->form()->update($id);
        }
    }
}
