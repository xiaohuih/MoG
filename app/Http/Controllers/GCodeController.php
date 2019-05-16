<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Input;
use App\Models\GCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form;
use Encore\Admin\Form\Builder;
use App\Admin\Extensions\Grid\SwitchDisplay;
use App\Admin\Extensions\Grid\ConfirmButton;

class GCodeController extends Controller
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
            ->header(trans('game.gcode'))
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
            ->header(trans('game.gcode'))
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
            ->header(trans('game.gcode'))
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
            ->header(trans('game.gcode'))
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
        $grid = new Grid(new GCode);
        // 筛选
        $grid->filter(function($filter){
            $filter->like('name', trans('game.info.name'));
            $filter->like('platform', trans('game.info.platform'));
        });
        // 行操作
        $grid->actions(function ($actions) {
            if ($actions->row['status'] == 1) {
                $actions->disableEdit();
            }
        });
        // 倒序
        $grid->model()->orderBy('created_at', 'desc');
        // 列
        $grid->id('ID');
        $grid->name(trans('game.info.name'))->display(function ($name) {
            return "<a href='gcode/export/{$this->id}' target='_blank'>{$name}</a>";
        });
        $options = collect(GCode::$types)->map(function ($item) {
            return trans('game.gcodes.' . $item);
        })->all();
        $grid->type(trans('game.info.type'))->using($options)->label('primary');
        $grid->platform(trans('game.info.platform'));
        $grid->group(trans('game.info.group'));
        $grid->column('begintime', trans('game.info.begintime'))->sortable();
        $grid->column('endtime', trans('game.info.endtime'))->sortable();
        $grid->status(trans('game.info.status'))->displayUsing(SwitchDisplay::Class);

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
        $show = new Show(GCode::findOrFail($id));
        $show->id('ID');
        $show->name(trans('game.info.name'));
        $options = collect(GCode::$types)->map(function ($item) {
            return trans('game.gcodes.' . $item);
        })->all();
        $show->type(trans('game.info.type'))->using($options)->label('primary');
        $show->key(trans('game.info.key'));
        $show->count(trans('game.info.count'));
        $show->platform(trans('game.info.platform'));
        $show->group(trans('game.info.group'));
        $show->begintime(trans('game.info.begintime'));
        $show->endtime(trans('game.info.endtime'));
        $show->mail(trans('game.info.mail'))->unescape()->as(function ($value) {
            return '<code>'.json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).'</code>';
        });

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
        $form = new Form(new GCode);
        $form->display('id');
        $form->text('name', trans('game.info.name'))->rules('required|max:50');
        $options = collect(GCode::$types)->map(function ($item) {
            return trans('game.gcodes.' . $item);
        })->all();
        $form->select('type', trans('game.info.type'))->options($options)->rules('required');
        $form->randpassword('key', trans('game.info.key'))->length(8)->rules('required|alpha_num|size:8');
        $form->number('count', trans('game.info.count'))->rules('required|regex:/^\d{1,7}$/');
        $form->text('platform', trans('game.info.platform'))->rules('nullable|regex:/^\d+$/');
        $form->text('group', trans('game.info.group'))->rules('nullable|regex:/^\d+$/');
        $form->datetime('begintime', trans('game.info.begintime'));
        $form->datetime('endtime', trans('game.info.endtime'));
        $form->embeds('mail', trans('game.info.mail'), function ($form) {
            $form->text('title', trans('game.info.title'))->rules('required|max:30');
            $form->textarea('content', trans('game.info.content'))->rows(3)->rules('required|max:255');
            $form->textarea('attachments', trans('game.info.attachments'))->rows(3)->rules('required|max:255|regex:/^(\d{1,},\d{1,})(;(\d{1,},\d{1,})){0,}$/')->help(trans('game.helps.items'));
        });
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
        $status = Input::get('status');
        if ($status) {
            if ($status == 'on') {
                return GCode::find($id)->publish();
            } else {
                return GCode::find($id)->unpublish();
            }
        } else if (Input::get('download')) {
            return GCode::find($id)->export();
        } else {
            return $this->form()->update($id);
        }
    }

    public function export($id)
    {
        return GCode::find($id)->export();
    }
}
