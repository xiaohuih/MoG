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
use GuzzleHttp\Client;

class MailController extends Controller
{
    use HasResourceActions;

    protected static $cmd = 10003;

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
        // 行操作
        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->append(new ConfirmButton($actions->getResource(), $actions->getRouteKey(), 'send', 'fa-paper-plane'));
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
        $show->status(trans('game.info.status'))->unescape()->as(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.sent');
                return "<span class='label label-success'>$name</span>";
            } else {
                $name = trans('game.info.unsent');
                return "<span class='label label-default'>$name</span>";
            }
        });

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
        $form->display('status', trans('game.info.status'))->with(function ($status) {
            if ($status == 1) {
                $name = trans('game.info.sent');
                return "<span class='label label-success'>$name</span>";
            } else {
                $name = trans('game.info.unsent');
                return "<span class='label label-default'>$name</span>";
            }
        });

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
            return $this->send($id);
        } else {
            return $this->form()->update($id);
        }
    }

    protected function send($id)
    {
        $params = [
            'funId' => 'PERFORM_PLAYER_ACTION',
            'id' => (int)$id,
            'action' => $action,
            'value' => $value
        ];
        $client = new Client();
        $res = $client->request('GET', config('game.gm.url'), [
            'timeout' => 10,
            'query' => [
                'CmdId' => static::$cmd,
                'ZoneId' => Game::getZone(),
                'params' => json_encode($params)
            ]
        ]);
        $data = json_decode($res->getBody(), true);

        return $data;
    }
}
