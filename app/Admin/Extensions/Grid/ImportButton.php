<?php

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Admin;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\AbstractTool;

class ImportButton extends AbstractTool
{
    /**
     * Render CreateButton.
     *
     * @return string
     */
    public function render()
    {
        $import = trans('admin.import');

        return <<<EOT

<div class="btn-group pull-right" style="margin-right: 10px">
    <a href="{$this->getImportUrl()}" class="btn btn-sm btn-success" title="{$import}">
        <i class="fa fa-upload"></i><span class="hidden-xs">&nbsp;&nbsp;{$import}</span>
    </a>
</div>

EOT;
    }

    protected function getImportUrl()
    {
        $queryString = '';

        return sprintf('%s/import%s',
            $this->grid->resource(),
            $queryString ? ('?'.$queryString) : ''
        );
    }
}
