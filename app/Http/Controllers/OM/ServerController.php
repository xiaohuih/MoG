<?php

namespace App\Http\Controllers\OM;

use App\Admin\Extensions\Grid\ConfirmButton;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use Illuminate\Support\Facades\Input;
use App\Models\OM;

class ServerController extends Controller
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
            ->header(trans('game.zone'))
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
            ->header(trans('game.zone'))
            ->description(trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * List interface.
     *
     * @return Array
     */
    public function list()
    {
        $state = Input::get('state');

        $result = [];
        $servers = OM\Server::list();
        
        foreach ($servers as $server) {
            if (!$state || $server['state'] == $state) {
                array_push($result, [
                    "id"=> $server['id'],
                    "text"=> $server['name']
                ]);
            }
        }
        return $result;
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OM\Server);
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
        $grid->name(trans('game.info.name'));
        $options = collect(OM\Server::$states)->map(function ($item) {
            $name = trans('game.serverstates.' . $item['name']);
            return "<span class='badge bg-{$item['style']}'>{$name}</span>";
        })->all();
        $grid->state(trans('game.info.state'))->using($options);

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new OM\Server);
        // 工具栏
        $form->tools(function (Form\Tools $tools) {
            $tools->disableView();
            $tools->disableDelete();
        });
        $form->disableViewCheck();
        $form->disableCreatingCheck();

        $form->display('id');
        $form->text('name', trans('game.info.name'))->rules('required|max:128');
        $options = collect(OM\Server::$states)->map(function ($item) {
            return trans('game.serverstates.' . $item['name']);
        })->all();
        $form->select('state', trans('game.info.state'))->options($options)->rules('required');

        return $form;
    }
}
