<?php

namespace App\Http\Controllers\OM;

use App\Models\OM\ClientPatch;
use App\Admin\Extensions\Grid\ConfirmButton;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class ClientPatchController extends Controller
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
            ->header(trans('game.patch'))
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
            ->header(trans('game.patch'))
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
            ->header(trans('game.patch'))
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
            ->header(trans('game.patch'))
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
        $grid = new Grid(new ClientPatch);
        // 导出按钮
        $grid->disableExport();
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'perform', 'fa-paper-plane'));
        });
        // 列
        $grid->type('ID');
        $grid->name(trans('game.info.name'));
        $grid->version(trans('game.info.version'));
        $grid->platform(trans('game.info.platform'));
        $grid->file(trans('game.info.file'));

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new ClientPatch);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
        });
        $form->display('id');
        
        $form->text('name', trans('game.info.name'))->rules('required|max:50');
        $form->text('version', trans('game.info.version'))->rules('required|max:50');
        $form->text('platform', trans('game.info.platform'))->rules('required');
        $form->largefile('file', trans('game.info.file'))->rules('required');
        
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
        if (Input::get('perform')) {
            return ClientPatch::find($id)->perform();
        } else {
            return $this->form()->update($id);
        }
    }
}
