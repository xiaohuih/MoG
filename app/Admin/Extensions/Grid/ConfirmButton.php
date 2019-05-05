<?php

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Admin;

class ConfirmButton
{   
    /**
     * @var string
     */
    protected $resource;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $action;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    protected $className;

    public function __construct($resource, $key, $action, $icon)
    {
        $this->resource = $resource;
        $this->key = $key;
        $this->action = $action;
        $this->icon = $icon;
    }
    
    /**
     * Get select class name.
     *
     * @return string
     */
    protected function getElementClassName()
    {
        if (!$this->className) {
            $this->className = uniqid().'-kick-button';
        }

        return $this->className;
    }

    protected function script()
    {
        $title_confirm = trans('game.actions.title_confirm', ['action' => trans('game.actions.'.$this->action)]);
        $confirm = trans('admin.confirm');
        $cancel = trans('admin.cancel');

        return <<<SCRIPT

$('.{$this->getElementClassName()}').unbind('click').click(function() {

    var pk = $(this).data('key');

    swal({
        title: "$title_confirm",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "$confirm",
        showLoaderOnConfirm: true,
        cancelButtonText: "$cancel",
        preConfirm: function() {
            return new Promise(function(resolve) {
                $.ajax({
                    method: 'POST',
                    url: '{$this->resource}/' + pk,
                    data: {
                        "{$this->action}": 1,
                        _method:'PUT',
                        _token:LA.token,
                    },
                    success: function (data) {
                        $.pjax.reload('#pjax-container');

                        resolve(data);
                    }
                });
            });
        }
    }).then(function(result) {
        var data = result.value;
        if (typeof data === 'object') {
            if (data.status) {
                swal(data.message, '', 'success');
            } else {
                swal(data.message, '', 'error');
            }
        }
    });
});

SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        $tooltip = trans('game.actions.'.$this->action);

        return <<<EOT

<a href="javascript:void(0);" class="{$this->getElementClassName()}" data-key="{$this->key}" data-toggle="tooltip" title="{$tooltip}">
    <i class="fa {$this->icon}"></i>
</a>

EOT;
    }

    public function __toString()
    {
        return $this->render();
    }
}
