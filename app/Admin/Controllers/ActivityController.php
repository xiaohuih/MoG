<?php

namespace App\Admin\Controllers;

use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Table;

class ActivityController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('活动')
            ->description('列表')
            ->body($this->view());
    }

    /**
     * Make a view render.
     *
     * @return view
     */
    protected function view()
    {
        return view('activity.list');
    }
}
