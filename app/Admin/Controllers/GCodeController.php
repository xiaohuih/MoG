<?php

namespace App\Admin\Controllers;

use App\Models\GCode;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Show;
use Encore\Admin\Form\Builder;

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
            ->header(trans('game.gcodes'))
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
            ->header(trans('game.gcodes'))
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
            ->header(trans('game.gcodes'))
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
            ->header(trans('game.gcodes'))
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
        // 批量操作
        $grid->tools(function ($tools) {
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });
        // 行操作
        $grid->actions(function ($actions) {
            $changable = !$actions->row->is_config;
            if (!$changable) {
                $actions->disableEdit();
                $actions->disableDelete();
            }
        });

        // 列
        $grid->id('ID');
        $grid->name(trans('game.gcode.name'));
        $grid->type(trans('game.gcode.type'))->display(function ($type) {
            if ($type == GCode::TYPE_NOLIMIT) {
                return trans('game.gcode.type_nolimit');
            } else if ($type == GCode::TYPE_ONCE) {
                return trans('game.gcode.type_once');
            }
            return 'unknown';
        });
        $grid->platform(trans('game.gcode.platform'));
        $grid->group(trans('game.gcode.group'));
        $grid->column('begintime', trans('game.gcode.begintime'))->display(function ($begintime) {
            return empty($begintime) ? '--' : date('Y-m-d H:i:s', $begintime);
        });
        $grid->column('endtime', trans('game.gcode.endtime'))->display(function ($endtime) {
            return empty($endtime) ? '--' : date('Y-m-d H:i:s', $endtime);
        });

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
        $show->name(trans('game.gcode.name'));
        $show->type(trans('game.gcode.type'))->as(function ($type) {
            if ($type == GCode::TYPE_NOLIMIT) {
                return trans('game.gcode.type_nolimit');
            } else if ($type == GCode::TYPE_ONCE) {
                return trans('game.gcode.type_once');
            }
            return 'unknown';
        });
        $show->platform(trans('game.gcode.platform'));
        $show->group(trans('game.gcode.group'));
        $show->key(trans('game.gcode.key'));
        $show->begintime(trans('game.gcode.begintime'))->as(function ($begintime) {
            return empty($begintime) ? '--' : date('Y-m-d H:i:s', $begintime);
        });
        $show->endtime(trans('game.gcode.endtime'))->as(function ($endtime) {
            return empty($endtime) ? '--' : date('Y-m-d H:i:s', $endtime);
        });
        $show->title(trans('game.gcode.mail.title'));
        $show->content(trans('game.gcode.mail.content'));
        $show->items(trans('game.gcode.mail.attachments'));

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
        //$form->action(admin_base_path('auth/menu'));
        $form->select('type', trans('game.gcode.type'))->options([
            GCode::TYPE_NOLIMIT => trans('game.gcode.type_nolimit'), 
            GCode::TYPE_ONCE => trans('game.gcode.type_once')
        ]);
        $form->text('name', trans('game.gcode.name'));
        $form->text('platform', trans('game.gcode.platform'));
        $form->text('group', trans('game.gcode.group'));
        $form->text('key', trans('game.gcode.key'));
        $form->datetime('begintime', trans('game.gcode.begintime'));
        $form->datetime('endtime', trans('game.gcode.endtime'));

        $form->text('title', trans('game.gcode.mail.title'));
        $form->textarea('content', trans('game.gcode.mail.content'))->rows(3);
        $form->textarea('items', trans('game.gcode.mail.attachments'))->rows(3);

        return $form;
    }

    public function eform($id)
    {
        $form = $this->form();
        
        $form->builder()->setMode(Builder::MODE_EDIT);
        $form->builder()->setResourceId($id);

        $form->setFieldValue($id);
        
        $builder = $form->model();

        $form->model = GCode::findOrFail($id);

//        static::doNotSnakeAttributes($this->model);

        $data = $form->model()->toArray();

        $form->builder->fields()->each(function (Field $field) use ($data) {
            if (!in_array($field->column(), $form->ignored)) {
                $field->fill($data);
            }
        });
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
        return $this->form()->update($id);
    }

    /**
     * Destroy data entity and remove files.
     *
     * @param $id
     *
     * @return mixed
     */
    public function destroy($id)
    {
        collect(explode(',', $id))->filter()->each(function ($id) {
            $model = GCode::findOrFail($id);
            $model->delete();
        });

        return response()->json([
            'status'  => true,
            'message' => trans('admin.delete_succeeded'),
        ]);
    }
}
