<?php

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;

class CreateButton extends AbstractTool
{
    /**
     * Render Create button.
     *
     * @return string
     */
    public function render()
    {
        $new = trans('admin.new');
        $create = trans('admin.create');
        $import = trans('admin.import');

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a class="btn btn-sm btn-success" title="{$new}"><i class="fa fa-plus"></i><span class="hidden-xs"> {$new}</span></a>
    <button type="button" class="btn btn-sm btn-success dropdown-toggle" data-toggle="dropdown">
        <span class="caret"></span>
        <span class="sr-only">Toggle Dropdown</span>
    </button>
    <ul class="dropdown-menu" role="menu">
        <li><a href="{$this->grid->getCreateUrl()}">{$create}</a></li>
        <li><a href="{$this->getImportUrl('')}">{$import}</a></li>
    </ul>
</div>
EOT;
    }

    /**
     * Get import url.
     *
     * @return string
     */
    public function getImportUrl()
    {
        $queryString = '';

        return sprintf('%s/import%s',
            $this->grid->resource(),
            $queryString ? ('?'.$queryString) : ''
        );
    }
}
