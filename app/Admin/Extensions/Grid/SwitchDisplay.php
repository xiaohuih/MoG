<?php

namespace App\Admin\Extensions\Grid;

use Encore\Admin\Admin;
use Encore\Admin\Grid\Displayers\SwitchDisplay as SwitchDisplayBase;

class SwitchDisplay extends SwitchDisplayBase
{
    public function display($states = [])
    {
        $this->updateStates($states);

        $name = $this->column->getName();

        $class = 'grid-switch-'.str_replace('.', '-', $name);

        $keys = collect(explode('.', $name));
        if ($keys->isEmpty()) {
            $key = $name;
        } else {
            $key = $keys->shift().$keys->reduce(function ($carry, $val) {
                return $carry."[$val]";
            });
        }

        $script = <<<EOT

$('.$class').bootstrapSwitch({
    size:'mini',
    onText: '{$this->states['on']['text']}',
    offText: '{$this->states['off']['text']}',
    onColor: '{$this->states['on']['color']}',
    offColor: '{$this->states['off']['color']}',
    onSwitchChange: function(event, state){

        $(this).val(state ? 'on' : 'off');

        var pk = $(this).data('key');
        var value = $(this).val();

        $.ajax({
            url: "{$this->grid->resource()}/" + pk,
            type: "POST",
            data: {
                "$key": value,
                _token: LA.token,
                _method: 'PUT'
            },
            success: function (data) {
                if (data.status == true) {
                    $.pjax.reload('#pjax-container');
                    if (data.message) {
                        toastr.success(data.message);
                    }
                } else {
                    $('.$class').bootstrapSwitch('state', !state, true);
                    if (data.message) {
                        toastr.error(data.message);
                    }
                }
            }
        });
    }
});

EOT;

        Admin::script($script);

        $key = $this->row->{$this->grid->getKeyName()};

        $checked = $this->states['on']['value'] == $this->value ? 'checked' : '';

        return <<<EOT
        <input type="checkbox" class="$class" $checked data-key="$key" />
EOT;
    }
}
